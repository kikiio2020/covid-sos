<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
        $this->middleware('throttle:60,1');
    }
    
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        //$user = User::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request): Response
    {
        \Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
        ])->validate();
        
        $user = auth()->user();
        $user->name = $request->name;
        $user->save();
        
        return response('', Response::HTTP_OK);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request): Response
    {
        $today = today() ;
        $fourteenDaysAgo = clone $today;
        $fourteenDaysAgo->subtract('day', 14);
        \Validator::make($request->all(), [
            'status' => 'integer|required',
            'quarantinedDate' => "nullable|before_or_equal:{$today}|after_or_equal:{$fourteenDaysAgo}",
        ])->validate();
        
        $user = auth()->user();
        $user->status = $request->status;
        $user->quarantined_at = $request->quarantinedDate;
        $user->status_set_at = today();
        $user->save();
        
        return response('', Response::HTTP_OK);
    }
    
    /**
     * @param Request $request
     * @return Response
     */
    public function changePassword(Request $request): Response
    {
        \Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ])->validate();
        
        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->save();
        
        return response('', Response::HTTP_OK);
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
    
    /**
     * @param Request $request
     * @return Response
     */
    public function removeCache(Request $request): Response
    {
        \Validator::make($request->all(), [
            'cache' => 'string',
        ])->validate();
        Cache::forget($request->cache);
    }
}
