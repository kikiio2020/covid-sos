<?php

use App\Http\Middleware\RequestStatusCanView;
use App\SosRequest;
use App\Http\Middleware\RequestOwnerOnly;
use App\Http\Middleware\PledgedRequestOnly;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Public routes
 */

\Route::get('/', function () {
    return view('welcome2');
})->middleware('guest')->name('welcome');
\Route::get('/about', function () {
    return view('about');
});
\Route::get('/aboutHujoCoin', function () {
    return view('abouthujocoin');
});

/**
 * Behind the wall routes
 */
\Auth::routes(['verify' => true]);
\Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');
\Route::get('/setstatus', 'HomeController@setstatus')->name('setstatus');
\Route::get('/profile', 'HomeController@profile')->name('profile');
\Route::get('/shoplist', 'HomeController@shoplist')->name('shoplist');
\Route::get('/sosRequest/{sosRequest}/accept', 'HomeController@sosRequestStandAloneAccept')
    ->middleware(
        [
            RequestStatusCanView::class . ':' . SosRequest::STATUS_PENDING,
            PledgedRequestOnly::class,
            RequestOwnerOnly::class,        
        ]
    )
    ->name('sosRequestStandAloneAccept');
\Route::get('/sosRequest/{sosRequest}/inProgress', 'HomeController@sosRequestStandAloneInProgressView')
    ->middleware(RequestStatusCanView::class . ':' . SosRequest::STATUS_IN_PROGRESS)    
    ->name('sosRequestStandAloneInProgressView');
\Route::get('/sosRequest/{sosRequest}/history', 'HomeController@sosRequestStandAloneHistoryView')
    ->middleware(RequestStatusCanView::class . ':' . SosRequest::STATUS_COMPLETED)
    ->name('sosRequestStandAloneHistoryView');

//\Route::get('/webapi/users/', 'Api\UserController@show');
\Route::put('/webapi/user/status', 'Api\UserController@updateStatus');
\Route::put('/webapi/user', 'Api\UserController@update');
\Route::put('/webapi/user/password', 'Api\UserController@changePassword');

/**
 * Cache
 */
//\Route::get('/webapi/user/showAlertCache/{alert}', 'Api\UserController@getAlertMessageCache');
//\Route::put('/webapi/user/showAlertCache/{alert}', 'Api\UserController@putAlertMessageCache');
//\Route::delete('/webapi/user/showAlertCache/{alert}', 'Api\UserController@clearAlertMessageCache');

\Route::ApiResources([
    '/webapi/user/showAlertCache' => 'Api\ShowAlertMessageController',
]);
\Route::put('/webapi/user/updateHomeTabIndexCache', 'Api\UserController@updateHomeTabIndexCache');
\Route::put('/webapi/user/removeHomeTabIndexCache', 'Api\UserController@removeHomeTabIndexCache');
//\Route::put('/webapi/user/flushCache', 'Api\UserController@flushCache'); //testing only


//Why this doesn't work???
/*\Route::ApiResources([
    '/webapi/sos' => 'Api\SosController',
]);*/

/**
 * webapi/sosRequest
 **/
\Route::get('webapi/sosRequest/inProgressView', 'Api\SosRequestController@inProgressView');

/*
Route::get('webapi/person', function(){
    $persons = new Collection();
    $persons->add([
        'id' => 1,
        'name' => 'Rachel',
        'email' => 'rachel@smallthings.in',
        'phone' => '12312345',
        'disabledActions' => ['complete'],
    ]);
    $persons->add([
        'id' => 2,
        'name' => 'Estha',
        'email' => 'estha@smallthings.in',
        'phone' => '33334445',
    ]);
    
    $resource = JsonResource::make($persons);
    
    return $resource;
});
*/

\Route::get('webapi/sosRequest/pendingsView', 'Api\SosRequestController@pendingsView');
\Route::get('webapi/sosRequest/historyView', 'Api\SosRequestController@historyView');
\Route::get('webapi/sosRequest/nearbyView', 'Api\SosRequestController@nearbyView');
\Route::put('webapi/sosRequest/pledgeRequest/{sosRequest}', 'Api\SosRequestController@pledge');
\Route::put('webapi/sosRequest/cancelRequest/{sosRequest}', 'Api\SosRequestController@cancelRequest');
\Route::put('webapi/sosRequest/acceptPledge/{sosRequest}', 'Api\SosRequestController@accept');
\Route::put('webapi/sosRequest/rejectPledge/{sosRequest}', 'Api\SosRequestController@reject');
\Route::put('webapi/sosRequest/cancelPledge/{sosRequest}', 'Api\SosRequestController@cancelPledge');
\Route::put('webapi/sosRequest/completeRequest/{sosRequest}', 'Api\SosRequestController@complete');

\Route::ApiResources([
    '/webapi/sosRequest' => 'Api\SosRequestController',
]);

/**
 * webapi/sos
 **/
\Route::get('webapi/sos/sosView', 'Api\SosController@sosView');
/*\Route::ApiResources([
    '/webapi/sos' => 'Api\SosController',
]);*/
\Route::get('webapi/sos', 'Api\SosController@index');
\Route::get('webapi/sos/{sos}', 'Api\SosController@show');
\Route::post('webapi/sos', 'Api\SosController@store');
\Route::put('webapi/sos/{sos}', 'Api\SosController@update');
\Route::delete('webapi/sos/{sos}', 'Api\SosController@destroy');

//\Route::get('webapi/shoplist/shoplistItem/{shoplist}', 'Api\ShoplistItemController@index');
/*\Route::resource('/webapi/shoplistItem', 'Api\ShoplistItemController')->only([
    'show', 'store', 'update', 'destroy'
]);
*/

\Route::resource('/webapi/item', 'Api\ItemController')->only(['index']);

\Route::ApiResources([
    '/webapi/sos/{sos}/shoplistItem' => 'Api\ShoplistItemController',
]);

//Hujo Coin
\Route::get('/hujoCoin', 'HomeController@hujoCoin')->name('hujocoin');
\Route::get('/hujoPay/{sosRequest}', 'HomeController@hujoPay')
    ->middleware([
        RequestStatusCanView::class . ':' . SosRequest::STATUS_IN_PROGRESS,
        RequestOwnerOnly::class,
    ])
    ->name('hujopay');
\Route::put('/webapi/hujoCoin/logEvent', 'Api\HujoCoinController@logEvent');
\Route::put('/webapi/hujoCoin/withdraw', 'Api\HujoCoinController@withdraw');
\Route::put('/webapi/hujoCoin/reinstate', 'Api\HujoCoinController@reinstate');
\Route::ApiResources([
    '/webapi/hujoCoin' => 'Api\HujoCoinController',
]);

