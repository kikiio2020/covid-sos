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
        return SosRequestResource::collection(auth()->user()->sosRequest()->pending()->get());
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
        $resultArray = array_map(
            function($item){
                //TODO Refactor to move to resource
                $item->distance_km = round($item->distance / 1000, 2);
                $item->creator = ($item->{'creator.name'} ?: $item->{'creator.email'}) . ' @ ' . $item->{'creator.address'};
                //TODO add Hujo field
                
                return (array) $item;
            }, 
            $result->toArray()
        );
        
        //TODO Refactor to move to resource
        return  [
            'data' => $resultArray
            
        ];
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
    
    public function pledge(int $sosRequestId): Response
    {
        DB::transaction(function() use ($sosRequestId) {
            $sosRequest = DB::table('sos_requests')->where('id', '=', $sosRequestId)->sharedLock()->first();
            
            if ($sosRequest->responded_by) {
                abort(Response::HTTP_CONFLICT, 'Sorry this request is already taken.');
                return false;
            }
            
            $values = [
                'responded_by' => auth()->user()->id,
                'status' => SosRequest::STATUS_IN_PROGRESS,
            ];
            DB::table('sos_requests')->where('id', '=', $sosRequestId)->update($values);
        }, 5);
        
        $sosRequest = SosRequest::find($sosRequestId);
        $sosRequest->user->notify(new RequestPledged($sosRequest));
        $this->clearNearbyCache($sosRequest);
        
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
