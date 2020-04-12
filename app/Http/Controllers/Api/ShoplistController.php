<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Shoplist;
use App\Http\Resources\Shoplist as ShoplistResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ShoplistController extends Controller
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
        return ShoplistResource::collection(auth()->user()->shoplist()->get());
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
            'name' => 'required',
        ])->validate();
        
        $shoplist = new Shoplist();
        $shoplist->fill($request->all());
        $shoplist->user_id = auth()->user()->id;
        $shoplist->save();
        
        return response(new ShoplistResource($shoplist), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Shoplist $shoplist
     * @return \Illuminate\Http\Response
     */
    public function show(Shoplist $shoplist)
    {
        return new ShoplistResource($shoplist);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Shoplist $shoplist
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shoplist $shoplist)
    {
        if (!$shoplist->id) {
            abort(Response::HTTP_NOT_FOUND);
        }
        \Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'sometimes|required',
        ])->validate();
        
        $shoplist->fill($request->all());
        $shoplist->save();
        
        return response('', Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Shoplist $shoplist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shoplist $shoplist)
    {
        if (!$shoplist->id) {
            abort(Response::HTTP_NOT_FOUND);
        }
        $shoplist->shoplistItem()->delete();
        $shoplist->delete();
        
        return response('', Response::HTTP_OK);
    }
}
