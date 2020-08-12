<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HujoCoin as HujoCoinResource;
use Illuminate\Http\Request;
use App\HujoCoin;
use App\HujoCoinTx;
use Illuminate\Http\Response;
use App\Rules\Probably256Hex;
use App\Notifications\HujoCoinEnrolled;
use App\Notifications\HujoCoinWithdrawn;
use App\Notifications\HujoCoinReinstated;

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
            'crypto_address' => 'required|unique:App\HujoCoin',
            'transaction_hash' => [
                'required',
                new Probably256Hex(),
            ],
        ])->validate();
        
        $hujoCoin = new HujoCoin();
        $hujoCoin->fill($request->all());
        $hujoCoin->save();
        $hujoCoinTx = new HujoCoinTx();
        $hujoCoinTx->fill(array_merge(
            [
                'function' => 'mintEnrol',
                'reference_id' => $hujoCoin->id,
            ],
            $request->all()
        ));
        $hujoCoinTx->save();
        
        $request->user()->notify(new HujoCoinEnrolled($hujoCoin));
        
        \Log::channel('bookkeeping')->info(
            'User[' . $request->user()->id . ']'
            . ' enrols Hujo Coin:'
            . ' Tx[' . $request->transaction_hash . ']'
            . ' Wallet[' . $request->crypto_address . ']'
        );
        
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
    
    public function logEvent(Request $request)
    {
        \Log::channel('bookkeeping')->info(
            '[HujoCoin event]: '
            . json_encode($request->all())
        );
        
        return response([], Response::HTTP_OK);
    }
    
    /**
     * Unenroll Hujo
     * - soft delete Hujo record
     * - send notification
     * 
     * @param Request $request
     */
    public function withdraw(Request $request)
    {
        \Validator::make($request->all(), [
            'hujoBalance' => 'required|numeric',
            'enrollmentDate' => 'required|Date',
            'lastTransactionDateUnix' => 'required|numeric',
        ])->validate();
        
        $hujo = $request->user()->hujoCoin()->first();
        $hujo->delete();
        
        \Log::channel('bookkeeping')->info(
            'User[' . $request->user()->id . ']'
            . ' Withdrawn from Hujo Coin:'
            . ' Balance[' . $request->hujoBalance . ']'
            . ' enrollmentDate[' . $request->enrollmentDate . ']'
            . ' lastTransactionDate[' . $request->lastTransactionDate . ']'
            );
        $request->user()->notify(new HujoCoinWithdrawn(
            $request->hujoBalance ,
            $request->enrollmentDate,
            date('Y-m-d', $request->lastTransactionDateUnix)
        ));
        
        return response('', Response::HTTP_OK);
    }
    
    public function reinstate(Request $request)
    {
        \Validator::make($request->all(), [
            'withdrawnHujoCoinId' => 'required|numeric',
        ])->validate();
        
        $hujoCoin = HujoCoin::withTrashed()->find($request->withdrawnHujoCoinId);
        if (!$hujoCoin) {
            abort(Response::HTTP_BAD_REQUEST, 'Not Registered.');
        }
        
        $hujoCoin->restore();
        
        \Log::channel('bookkeeping')->info(
            'User[' . $request->user()->id . ']'
            . ' Reinstated Hujo Coin:'
            . ' Balance[' . $request->hujoBalance . ']'
            . ' enrollmentDate[' . $request->enrollmentDate . ']'
            . ' lastTransactionDate[' . $request->lastTransactionDate . ']'
        );
        
        $request->user()->notify(new HujoCoinReinstated($hujoCoin));
        
        return response('', Response::HTTP_OK);
    }
}
