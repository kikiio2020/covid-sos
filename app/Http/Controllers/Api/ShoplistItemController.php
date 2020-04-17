<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\ShoplistItem;
use App\Http\Resources\ShoplistItem as ShoplistItemResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use App\Sos;

class ShoplistItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }
    
    /**
     * Display a listing of the resource.
     * @param Sos $sos
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(Sos $sos)
    {
        return ShoplistItemResource::collection($sos->shoplistItem()->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        \Validator::make($request->all(), [
            'list_id' => 'required|integer',
            'item_id' => 'required|integer',
            'quantity' => 'required',
            'max_dollar' => 'required|regex:/^\d{1,4}(\.\d{1,2})?$/',
        ])->validate();
        
        $shoplistItem = new ShoplistItem();
        $shoplistItem->fill($request->all());
        $shoplistItem->save();
        
        return response('', Response::HTTP_CREATED);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param Sos $sos
     * @param ShoplistItem $shoplistItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sos $sos, ShoplistItem $shoplistItem): Response
    {
        if (!$shoplistItem->id) {
            abort(Response::HTTP_NOT_FOUND);
        }
        
        \Validator::make($request->all(), [
            'id' => 'required|integer',
            'list_id' => 'sometimes|required|integer',
            'item_id' => 'sometimes|required|integer',
            'quantity' => 'sometimes|required',
            'max_dollar' => 'sometimes|required|regex:/^\d{1,4}(\.\d{1,2})?$/',
        ])->validate();
        
        $shoplistItem->fill($request->all());
        $shoplistItem->save();
        
        return response('', Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Sos $sos
     * @param ShoplistItem $shoplistItem
     * @return Response
     */
    public function destroy(Sos $sos, ShoplistItem $shoplistItem): Response
    {
        if (!$shoplistItem->id) {
            abort(Response::HTTP_NOT_FOUND);
        }
        $shoplistItem->delete();
        
        return response('', Response::HTTP_OK);
    }
}
