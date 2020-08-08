<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ShowAlertMessage;
use Illuminate\Http\Response;

class ShowAlertMessageController extends Controller
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
    public function index()
    {
        return new Response([], Response::HTTP_NOT_IMPLEMENTED);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Response
    {
        return new Response([], Response::HTTP_NOT_IMPLEMENTED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $alert): ShowAlertMessage
    {
        \Validator::make(['alert' => $alert], [
            'alert' => 'required|string',
        ])->validate();
        
        return new ShowAlertMessage([
            'show' => auth()->user()->getAlertMessageCache($alert),
            'alert' => $alert,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $alert): ShowAlertMessage
    {
        \Validator::make(['alert' => $alert], [
            'alert' => 'required|string',
        ])->validate();
        
        auth()->user()->putAlertMessageCache($alert);
        
        return new ShowAlertMessage([
            'show' => auth()->user()->getAlertMessageCache($alert),
            'alert' => $alert,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $alert): Response
    {
        \Validator::make(['alert' => $alert], [
            'alert' => 'required|string',
        ])->validate();
        
        auth()->user()->clearAlertMessageCache($alert);
        
        return response('', Response::HTTP_OK);
    }
}
