<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Ask;
use App\Http\Resources\Ask as AskResource;
use App\Http\Resources\AskView as AskViewResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;

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
        return AskResource::collection(
            Ask::inProgress()->createdBy(auth()->user())->orWhere->respondedBy(auth()->user())->get()
        );
    }
    
    public function pendingsView()
    {
        return AskResource::collection(auth()->user()->ask()->whereIn(
            'status',
            [ Ask::STATUS_PENDING ]
            )->get());
    }
    
    public function historiesView()
    {
        $userId = auth()->user()->id;
        
        return AskResource::collection(
            Ask::where('status', '=', Ask::STATUS_COMPLETED )
            ->whereRaw(
                "responded_by = {$userId} OR created_by = {$userId}"
            )->get()
            );
    }
    
    public function nearbyView()
    {
        $result = auth()->user()->getNearbyResult();
        $resultArray = array_map(
            function($item){
                //TODO Refactor move to resource
                $item->distance_km = round($item->distance / 1000, 2);
                $item->vendor = $item->vendor_name . ' @ ' . $item->vendor_address;
                $item->creator = ($item->{'creator.name'} ?: $item->{'creator.email'}) . ' @ ' . $item->{'creator.address'};
                
                return (array) $item;
            }, 
            $result->toArray()
        );
        
        //TODO Refactor move to resource
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
        ])->validate();
        
        if (Ask::STATUS_PENDING === $ask->status && $request->exists('status')) {
            $this->clearNearbyCache($ask);
        }
        
        $ask->fill($request->all());
        $ask->save();
        
        return response('', Response::HTTP_OK);
    }

    private function clearNearbyCache(Ask $ask): void
    {
        foreach($ask->getNearbyReverseCache() as $userId) {
            User::clearNearbyCacheById($userId);
        }
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
