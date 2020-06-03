<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\UnknownStatus;
use App\Ask;

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
    
    public function askStandAloneInProgressView(Ask $ask)
    {
        return view('standalone.ask', [
            'ask' => $ask,
            'isInProgress' => Ask::STATUS_IN_PROGRESS === $ask->status,
        ]);
    }
    
    public function askStandAloneHistoryView(Ask $ask)
    {
        return view('standalone.ask', [
            'ask' => $ask,
            'isInProgress' => Ask::STATUS_IN_PROGRESS === $ask->status,
        ]);
    }
    
}
