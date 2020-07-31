<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\SosRequest;
use App\Http\Resources\SosRequest as SosRequestResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use App\Http\Resources\InProgressView;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\RequestCompleted;
use App\Notifications\RequestPledged;
use App\Http\Resources\HistoryView;
use Illuminate\Database\Eloquent\Builder;
use App\Notifications\RequestNeedsApproval;
use App\HujoCoinTx;
use App\Http\Resources\NearbyView;
use App\Notifications\RequestRejected;
use App\Notifications\RequestCancelled;
use App\Notifications\RequestAccepted;
use App\Notifications\PledgeCancelled;

class SosRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return SosRequestResource::collection(auth()->user()->sosRequest()->get());
    }
    
    public function inProgressView()
    {
        $userId = auth()->user()->id;
        
        return InProgressView::collection(
            SosRequest::inProgress()->Where(
                function (Builder $query) use ($userId) {
                    $query->whereRaw("responded_by = {$userId} OR user_id = {$userId}");
                }
            )->get()
        );
    }
    
    public function pendingsView()
    {
        return SosRequestResource::collection(
            auth()->user()->sosRequest()->pending()->where('needed_by', '>', Carbon::now())->get()
            ->merge(
                auth()->user()->pledges()->pending()->where('needed_by', '>', Carbon::now())->get()
            )
        );
    }
    
    public function historyView()
    {
        $userId = auth()->user()->id;
        
        return HistoryView::collection(
            SosRequest::completed()->where(function(Builder $query) use($userId) {
                $query->whereRaw("responded_by = {$userId} OR user_id = {$userId}");
            })->get()
        );
    }
    
    public function nearbyView()
    {
        $result = auth()->user()->getNearbyResult();
        
        return NearbyView::collection($result);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        \Validator::make($request->all(), [
            'sos_id' => 'required|numeric',
            'chat' => 'nullable|json',
        ])->validate();
        
        $sosRequest = new SosRequest();
        $sosRequest->fill($request->all());
        $sosRequest->user_id = auth()->user()->id;
        $sosRequest->status = SosRequest::STATUS_PENDING;
        $sosRequest->save();
        
        return response(new SosRequestResource($sosRequest), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param SosRequest $sosRequest
     * @return \Illuminate\Http\Response
     */
    public function show(SosRequest $sosRequest)
    {
        return new SosRequestResource($sosRequest);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param SosRequest $sosRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SosRequest $sosRequest)
    {
        if (!$sosRequest->id) {
            abort(Response::HTTP_NOT_FOUND);
        }
        \Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'sometimes|required',
            'chat' => 'nullable|json',
        ])->validate();
        
        if (SosRequest::STATUS_PENDING === $sosRequest->status && $request->exists('status')) {
            $this->clearNearbyCache($sosRequest);
        }
        
        $sosRequest->fill($request->all());
        $sosRequest->chat = $this->truncateChat(json_decode($request->chat));
        $sosRequest->save();
        
        return response('', Response::HTTP_OK);
    }

    private function truncateChat(array $chat): array
    {
        return array_slice(
            array_map(
                function($item){
                    if ($item->message) {
                        $item->message = substr($item->message, 0, 250);
                    }
                    return $item;
                },
                $chat
            ),
            0,
            100
        );
    }
    
    /**
     * @param int $sosRequestId
     * @return Response
     */
    public function complete(Request $request, SosRequest $sosRequest): Response
    {
        \Validator::make($request->all(), [
            'transaction_hash' => 'sometimes|required',
        ])->validate();
        
        $user = auth()->user();
        $userId = $user->id;
        $sosRequestId = $sosRequest->id;        
        
        /**
         * Separate creating HujoCoinTx from DB::transaction because
         * we don't want to lose the txId in case the DB::trasaction aborts
         */
        if ($request->txId) {
            $hujoCoinTx = new HujoCoinTx();
            $fillables = [
                'user_id' => $userId,
                'function' => 'transferFrom',
                'reference_id' => $sosRequestId,
                'transaction_hash' => $request->transaction_hash,
            ];
            if ($userId === $sosRequest->user_id) {
                //only fill recepient_id when user is requester
                $fillables['recipient_id'] = $sosRequest->responded_by;
            }
            $hujoCoinTx->fill($fillables);
            $hujoCoinTx->save();
            //TODO send tx notification to both users
        }
        
        /**
         * Update request status
         */
        DB::transaction(function() use ($sosRequestId, $userId) {
            $sosRequest = DB::table('sos_requests')->where('id', '=', $sosRequestId)->sharedLock()->first();
            $values = [];
            $now = Carbon::now();
            
            if ($userId === $sosRequest->responded_by) {
                //Responder
                $values['responder_approved'] = $now;
                if ($sosRequest->user_approved) {
                    $values['status'] = SosRequest::STATUS_COMPLETED;
                }
            } else {
                //Requester
                $values['user_approved'] = $now;
                if ($sosRequest->responder_approved) {
                    $values['status'] = SosRequest::STATUS_COMPLETED;
                }
            }
            if (count($values)) {
                DB::table('sos_requests')->where('id', '=', $sosRequestId)->update($values);
            }
            
        }, 5);
     
        /**
         * Send notification
         */
        $sosRequest = SosRequest::find($sosRequestId);
        if (SosRequest::STATUS_COMPLETED === $sosRequest->status) {
            $user->notify(new RequestCompleted($sosRequest));
            $sosRequest->responder->notify(new RequestCompleted($sosRequest));
            
            return response('', Response::HTTP_OK);
        } 
        
        if ($userId === $sosRequest->responded_by) {
            //Responder
            $sosRequest->user->notify(new RequestNeedsApproval($sosRequest));
        } else {
            //Requestor
            $sosRequest->responder->notify(new RequestNeedsApproval($sosRequest));
        }
            
        return response('', Response::HTTP_OK);
    }
    
    private function clearNearbyCache(SosRequest $sosRequest): void
    {
        foreach($sosRequest->getNearbyReverseCache() as $userId) {
            User::clearNearbyCacheById($userId);
        }
    }
    
    public function pledge(SosRequest $sosRequest): Response
    {
        $sosRequestId = $sosRequest->id;
        if ($sosRequest->needed_by < Carbon::now()) {
            $logMsg = '[Request expired]:' . $sosRequest->toJson();
            \Log::channel('bookkeeping')->info($logMsg);
            abort(Response::HTTP_BAD_REQUEST, 'request_expired');
        }
        DB::transaction(function() use ($sosRequestId) {
            $sosRequest = DB::table('sos_requests')->where('id', '=', $sosRequestId)->sharedLock()->first();
            
            if ($sosRequest->responded_by) {
                abort(Response::HTTP_CONFLICT, 'Sorry this request is already taken.');
                return false;
            }
            
            $values = [
                'responded_by' => auth()->user()->id,
            ];
            DB::table('sos_requests')->where('id', '=', $sosRequestId)->update($values);
        }, 5);
        
        $sosRequest = SosRequest::find($sosRequestId);
        $sosRequest->user->notify(new RequestPledged($sosRequest));
        $this->clearNearbyCache($sosRequest);
        
        return response('', Response::HTTP_OK);
    }
    
    public function accept(SosRequest $sosRequest): Response
    {
        if ($sosRequest->user->id !== auth()->user()->id) {
            abort(Response::HTTP_FORBIDDEN, 'not_allowed');
        }
        if ($sosRequest->needed_by < Carbon::now()) {
            $logMsg = 'Request expired and can\'t be accepted. [Request]:' . $sosRequest->toJson();
            \Log::channel('bookkeeping')->info($logMsg);
            abort(Response::HTTP_BAD_REQUEST, 'request_expired');
        }
        if ($sosRequest->user->hujoCoin() && $sosRequest->responder->hujoCoin()) {
            $sosRequest->is_hujo = true;
        }
        $sosRequest->status = SosRequest::STATUS_IN_PROGRESS;
        $sosRequest->save();
        $sosRequest->user->notify(new RequestAccepted($sosRequest));
        $sosRequest->responder->notify(new RequestAccepted($sosRequest));
        
        \Log::channel('bookkeeping')->info(
            'Requestor[' . $sosRequest->user->id . ']'
            . ' accepts help for '
            . 'Request[' . $sosRequest->id . ']'
            . ' from '
            . 'Responder[' . $sosRequest->responder->id . ']'
            );
        
        return response('', Response::HTTP_OK);
    }
    
    /**
     * @param SosRequest $sosRequest
     * @return Response
     */
    public function reject(SosRequest $sosRequest): Response
    {
        if ($sosRequest->user->id !== auth()->user()->id) {
            abort(Response::HTTP_FORBIDDEN, 'not_allowed');
        }
        if ($sosRequest->needed_by < Carbon::now()) {
            $logMsg = 'Request expired and can\'t be rejected. [Request]:' . $sosRequest->toJson();
            \Log::channel('bookkeeping')->info($logMsg);
            abort(Response::HTTP_BAD_REQUEST, 'request_expired');
        }
        $sosRequest->responder->notify(new RequestRejected($sosRequest));
        $sosRequest->is_hujo = false;
        $sosRequest->responded_by = null;
        $sosRequest->save();
        
        \Log::channel('bookkeeping')->info(
            'Requestor[' . $sosRequest->user->id . ']'
            . ' rejects help for '
            . 'Request[' . $sosRequest->id . ']'
            . ' from '
            . 'Responder[' . $sosRequest->responder->id . ']'
        );
        
        return response('', Response::HTTP_OK);
    }
    
    /**
     * @param SosRequest $sosRequest
     * @return Response
     */
    public function cancelRequest(SosRequest $sosRequest): Response
    {
        if ($sosRequest->user->id !== auth()->user()->id) {
            abort(Response::HTTP_FORBIDDEN, 'not_allowed');
        }
        
        //Not yet pledged
        if (!$sosRequest->responded_by) {
            $logMsg = 'Requestor[' . $sosRequest->user->id . ']'
                . ' cancels '
                . 'Request[' . $sosRequest->id . ']'
                . '[Request removed]:' . $sosRequest->toJson();
            $sosRequest->delete();
            $this->clearNearbyCache($sosRequest);
            \Log::channel('bookkeeping')->info($logMsg);

            return response('', Response::HTTP_OK);
        }
        
        //Already pleged
        $logMsg = 'Requestor[' . $sosRequest->user->id . ']'
            . ' cancels '
            . 'Request[' . $sosRequest->id . ']'
            . ' pledged by '
            . 'Responder[' . $sosRequest->responder->id . ']: '
            . '[Request removed]:' . $sosRequest->toJson();
        $sosRequest->responder->notify(new RequestCancelled($sosRequest));
        $sosRequest->delete();
        //No need to clear Nearby cache requests are removed from cache when pledged 
        \Log::channel('bookkeeping')->info($logMsg);
        
        return response('', Response::HTTP_OK);
    }
    
    public function cancelPledge(SosRequest $sosRequest): Response
    {
        if (!$sosRequest->responder 
            || $sosRequest->responder->id !== auth()->user()->id
        ) {
            abort(Response::HTTP_FORBIDDEN, 'not_allowed');
        }
     
        $logMsg = 'Responder[' . $sosRequest->responder->id . ']'
            . ' cancels pledge to '
            . 'Request[' . $sosRequest->id . ']'
            . ' from '
            . 'Requestor[' . $sosRequest->user->id . ']';
        $sosRequest->user->notify(new PledgeCancelled($sosRequest));
        $sosRequest->responded_by = null;
        $sosRequest->is_hujo = false;
        $sosRequest->save();
        //Request will show up again on people's NearBy view the next day as their cache expires.
        \Log::channel('bookkeeping')->info($logMsg);
        
        return response('', Response::HTTP_OK);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param SosRequest $sosRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(SosRequest $sosRequest)
    {
        if (!$sosRequest->id) {
            abort(Response::HTTP_NOT_FOUND);
        }
        $sosRequest->delete();
        
        return response('', Response::HTTP_OK);
    }
}
