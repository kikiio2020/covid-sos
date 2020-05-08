<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Sos;
use App\Http\Resources\Sos as SosResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class SosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }
    
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        /*\Validator::make($request->all(), [
            'status' => 'nullable|integer',
        ])->validate();
     
        if ($request->status) {
            return SosResource::collection(auth()->user()->sos()->where('status', $request->status)->get());
        }
        
        return SosResource::collection(auth()->user()->sos);
        */
    }

    public function sosView(Request $request)
    {
        return SosResource::collection(auth()->user()->sos);
    }
    
    /*
    public function pledged()
    {
        return SosResource::collection(auth()->user()->pledged);
    }
    
    public function need()
    {
        return SosResource::collection(auth()->user()->sos()->whereIn(
            'status', 
            [ Sos::STATUS_PENDING ]
        )->get());
    }
    
    public function history()
    {
        $userId = auth()->user()->id;
        
        return SosResource::collection(
            Sos::where('status', '=', Sos::STATUS_COMPLETED )
                ->whereRaw(
                    "responded_by = {$userId} OR created_by = {$userId}"
                )->get()
        );
    }
    
    public function nearby()
    {
        //return SosResource::collection(auth()->user()->pledged);
    }
    */
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): Response
    {
        \Validator::make($request->all(), [
            'name' => 'required',
            'delivery_option' => 'required',
            'payment_option' => 'required',
        ])->validate();
        
        $sos = new Sos();
        $sos->fill($request->all());
        $sos->created_by = auth()->user()->id;
        $sos->save();
        
        return response(new SosResource($sos), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sos  $sos
     * @return \Illuminate\Http\Response
     */
    public function show(Sos $sos)
    {
        //return response()->json($sos->toArray());
        return new SosResource($sos);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sos  $sos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sos $sos): Response
    {
        if (!$sos->id) {
            abort(Response::HTTP_NOT_FOUND);
        }
        \Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'sometimes|required',
            'delivery_option' => 'sometimes|required',
            'payment_option' => 'sometimes|required',
        ])->validate();
        
        $sos->fill($request->all());
        $sos->save();
        
        return response(new SosResource($sos), Response::HTTP_OK);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sos  $sos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sos $sos): Response
    {
        if (!$sos->id) {
            abort(Response::HTTP_NOT_FOUND);
        }
        foreach (['receipt_image'] as $imageField) {
            if ($sos->$imageField && Storage::disk('uploads')->exists($sos->$imageField)) {
                Storage::disk('uploads')->delete($sos->$imageField);
            }
        }
        $sos->shoplistItem()->delete();
        $sos->delete();
        
        return response('', Response::HTTP_OK);
    }
}
