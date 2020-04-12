<?php

use Illuminate\Support\Facades\Route;

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

//\Route::get('/webapi/users/', 'Api\UserController@show');
\Route::put('/webapi/user/status', 'Api\UserController@updateStatus');
\Route::put('/webapi/user', 'Api\UserController@update');
\Route::put('/webapi/user/password', 'Api\UserController@changePassword');

\Route::put('/webapi/user/removeCache', 'Api\UserController@removeCache');

//Why this doesn't work???
/*\Route::ApiResources([
    '/webapi/sos' => 'Api\SosController',
]);*/

\Route::get('webapi/sos/pledged', 'Api\SosController@pledged');
\Route::get('webapi/sos/need', 'Api\SosController@need');
\Route::get('webapi/sos/history', 'Api\SosController@history');
\Route::get('webapi/sos/nearby', 'Api\SosController@nearby');

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
    '/webapi/shoplist/{shoplist}/shoplistItem' => 'Api\ShoplistItemController',
]);

\Route::ApiResources([
    '/webapi/shoplist' => 'Api\ShoplistController',
]);

