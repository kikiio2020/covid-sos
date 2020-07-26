<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HujoCoin as HujoCoinResource;
use Illuminate\Http\Request;
use App\HujoCoin;
use App\HujoCoinTx;
use Illuminate\Http\Response;

class HujoCoinController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): Response
    {
        \Validator::make($request->all(), [
            'user_id' => 'required',
            'crypto_address' => 'required',
            'transaction_hash' => 'required',
        ])->validate();
        
        $hujoCoin = new HujoCoin();
        $hujoCoin->fill($request->all());
        $hujoCoin->save();
        $hujoCoinTx = new HujoCoinTx();
        $hujoCoinTx->fill(array_merge(
            ['function' => 'mintEnrol'],
            $request->all()
        ));
        $hujoCoinTx->save();
        
        //TODO send notification for enrollment and txID
        
        
        return response(new HujoCoinResource($hujoCoin), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(HujoCoin $hujoCoin): Response
    {
        return new HujoCoinResource($hujoCoin);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
