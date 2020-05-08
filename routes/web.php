<?php

use Illuminate\Support\Facades\Route;
use App\Ask;
use App\Http\Middleware\RequestStatusCanView;

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

/**
 * Behind the wall routes
 */
\Auth::routes(['verify' => true]);
\Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');
\Route::get('/setstatus', 'HomeController@setstatus')->name('setstatus');
\Route::get('/profile', 'HomeController@profile')->name('profile');
\Route::get('/shoplist', 'HomeController@shoplist')->name('shoplist');
\Route::get('/ask/{ask}/inProgress', 'HomeController@askStandAloneInProgressView')
    ->middleware(RequestStatusCanView::class . ':' . Ask::STATUS_IN_PROGRESS)    
    ->name('askStandAloneInProgressView');
\Route::get('/ask/{ask}/history', 'HomeController@askStandAloneHistoryView')
->middleware(RequestStatusCanView::class . ':' . Ask::STATUS_COMPLETED)
    ->name('askStandAloneHistoryView');

//\Route::get('/webapi/users/', 'Api\UserController@show');
\Route::put('/webapi/user/status', 'Api\UserController@updateStatus');
\Route::put('/webapi/user', 'Api\UserController@update');
\Route::put('/webapi/user/password', 'Api\UserController@changePassword');

\Route::put('/webapi/user/removeCache', 'Api\UserController@removeCache');

//Why this doesn't work???
/*\Route::ApiResources([
    '/webapi/sos' => 'Api\SosController',
]);*/

/**
 * webapi/ask
 **/
\Route::get('webapi/ask/inProgressView', 'Api\AskController@inProgressView');
\Route::get('webapi/ask/pendingsView', 'Api\AskController@pendingsView');
\Route::get('webapi/ask/historyView', 'Api\AskController@historyView');
\Route::get('webapi/ask/nearbyView', 'Api\AskController@nearbyView');
\Route::put('webapi/ask/pledgeRequest/{ask}', 'Api\AskController@pledgeAsk');
\Route::put('webapi/ask/completeRequest/{ask}', 'Api\AskController@completeAsk');

\Route::ApiResources([
    '/webapi/ask' => 'Api\AskController',
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



