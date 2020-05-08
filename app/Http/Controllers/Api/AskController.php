<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Ask;
use App\Http\Resources\Ask as AskResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use App\Http\Resources\InProgressView;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\RequestCompleted;
use App\Notifications\RequestPledged;
use App\Http\Resources\HistoryView;

class AskController extends Controller
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
        return AskResource::collection(auth()->user()->ask()->get());
    }
    
    public function inProgressView()
    {
        $userId = auth()->user()->id;
        
        return InProgressView::collection(
            Ask::inProgress()->whereRaw("responded_by = {$userId} OR user_id = {$userId}")->get()
        );
    }
    
    public function pendingsView()
    {
        return AskResource::collection(auth()->user()->ask()->whereIn(
            'status',
            [ Ask::STATUS_PENDING ]
            )->get());
    }
    
    public function historyView()
    {
        $userId = auth()->user()->id;
        
        return HistoryView::collection(
            Ask::completed()->whereRaw("responded_by = {$userId} OR user_id = {$userId}")->get()
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
        
        $ask = new Ask();
        $ask->fill($request->all());
        $ask->user_id = auth()->user()->id;
        $ask->status = Ask::STATUS_PENDING;
        $ask->save();
        
        return response(new AskResource($ask), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Ask $ask
     * @return \Illuminate\Http\Response
     */
    public function show(Ask $ask)
    {
        return new AskResource($ask);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Ask $ask
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ask $ask)
    {
        if (!$ask->id) {
            abort(Response::HTTP_NOT_FOUND);
        }
        \Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'sometimes|required',
            'chat' => 'nullable|json',
        ])->validate();
        
        if (Ask::STATUS_PENDING === $ask->status && $request->exists('status')) {
            $this->clearNearbyCache($ask);
        }
        
        $ask->fill($request->all());
        $ask->chat = $this->truncateChat(json_decode($request->chat));
        $ask->save();
        
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
     * @param int $askId
     * @return Response
     */
    public function completeAsk(int $askId): Response
    {
        $user = auth()->user();
        $userId = $user->id;
        
        DB::transaction(function() use ($askId, $userId) {
            $ask = DB::table('asks')->where('id', '=', $askId)->sharedLock()->first();
            
            $values = [];
            $now = Carbon::now();
            
            //Responder completes
            if ($userId === $ask->responded_by) {
                $values['responder_approved'] = $now;
                if ($ask->user_approved) {
                    $values['status'] = Ask::STATUS_COMPLETED;
                }
            }
            
            //User completes
            if ($userId === $ask->user_id) {
                $values['user_approved'] = $now;
                if ($ask->responder_approved) {
                    $values['status'] = Ask::STATUS_COMPLETED;
                }
            }
            
            if (count($values)) {
                DB::table('asks')->where('id', '=', $askId)->update($values);
            }
            
        }, 5);
     
        $ask = Ask::find($askId);
        $user->notify(new RequestCompleted($ask));
        $ask->responder->notify(new RequestCompleted($ask));
        
        return response('', Response::HTTP_OK);
    }
    
    private function clearNearbyCache(Ask $ask): void
    {
        foreach($ask->getNearbyReverseCache() as $userId) {
            User::clearNearbyCacheById($userId);
        }
    }
    
    public function pledgeAsk(int $askId): Response
    {
        DB::transaction(function() use ($askId) {
            $ask = DB::table('asks')->where('id', '=', $askId)->sharedLock()->first();
            
            if ($ask->responded_by) {
                abort(Response::HTTP_CONFLICT, 'Sorry this request is already taken.');
                return false;
            }
            
            $values = [
                'responded_by' => auth()->user()->id,
                'status' => Ask::STATUS_IN_PROGRESS,
            ];
            DB::table('asks')->where('id', '=', $askId)->update($values);
        }, 5);
        
        $ask = Ask::find($askId);
        $ask->user->notify(new RequestPledged($ask));
        
        return response('', Response::HTTP_OK);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param Ask $ask
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ask $ask)
    {
        if (!$ask->id) {
            abort(Response::HTTP_NOT_FOUND);
        }
        $ask->delete();
        
        return response('', Response::HTTP_OK);
    }
}
