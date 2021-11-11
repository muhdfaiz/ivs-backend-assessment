<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
|--------------------------------------------------------------------------
| Routes related to authentication.
|--------------------------------------------------------------------------
*/
Route::post('/auth/access-token', 'AuthController@accessToken')->name('access_token');

Route::prefix('/events/subscribers')->name('event_subscribers.')->group(function() {
    Route::get('/', 'EventSubscriberController@index')->middleware('client')->name('index');
    Route::get('/{id}', 'EventSubscriberController@view')->middleware('client')->name('view');
    Route::post('/', 'EventSubscriberController@store')->name('store');
});
