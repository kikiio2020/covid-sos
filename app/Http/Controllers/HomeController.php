<?php

namespace App\Http\Controllers;

use App\Http\Middleware\UnknownStatus;
use App\SosRequest;
use Illuminate\Http\Response;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(UnknownStatus::class)->except('setstatus');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view(
            'home', 
            [
                'sosOptions' => auth()->user()->sos->map(function ($sos) {
                    return [
                        'value' => $sos->id,
                        'text' => $sos->name,
                    ];
                })
            ]
        );
    }
    
    public function setstatus()
    {
        return view('setstatus');
    }
    
    public function profile()
    {
        return view('profile');
    }
    
    public function shoplist()
    {
        return view('shoplist');
    }
    
    public function hujoCoin()
    {
        return view('hujocoin');
    }
    
    public function hujoPay(SosRequest $sosRequest)
    {
        if ($sosRequest->user_approved) {
            abort(Response::HTTP_FORBIDDEN, 'Oops! Looks like you have already completed this request.');
        }
        
        return view('hujopay', ['sosRequest' => $sosRequest]);
    }
    
    public function hujoAcceptPledge(SosRequest $sosRequest)
    {
        if ($sosRequest->user_approved) {
            abort(Response::HTTP_FORBIDDEN, 'Oops! Looks like you have already completed this request.');
        }
        
        return view('hujoacceptpledge', ['sosRequest' => $sosRequest]);
    }
    
    public function sosRequestStandAloneAccept(SosRequest $sosRequest)
    {
        return view('standalone.acceptpledge', [
            'sosRequest' => $sosRequest
        ]);
    }
    
    public function sosRequestStandAloneInProgressView(SosRequest $sosRequest)
    {
        return view('standalone.sosrequest', [
            'sosRequest' => $sosRequest,
            'isInProgress' => SosRequest::STATUS_IN_PROGRESS === $sosRequest->status,
        ]);
    }
    
    public function sosRequestStandAloneHistoryView(SosRequest $sosRequest)
    {
        return view('standalone.sosrequest', [
            'sosRequest' => $sosRequest,
            'isInProgress' => SosRequest::STATUS_IN_PROGRESS === $sosRequest->status,
        ]);
    }
    
}
