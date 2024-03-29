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


Route::prefix('v1')->group(function () {
    Route::get('chuckles', 'App\Http\Controllers\ChucklesController@get_chuckles');
    Route::get('chuckles/user', 'App\Http\Controllers\ChucklesController@get_user_chuckles');
    Route::post('chuckles/create', 'App\Http\Controllers\ChucklesController@create');
});

