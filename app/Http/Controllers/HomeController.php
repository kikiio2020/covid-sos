<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\UnknownStatus;

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
        return view('home');
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
}
