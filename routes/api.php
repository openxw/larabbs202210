<?php

use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthorizationsController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->name('api.v1')->group(function (){
    Route::post('users', 'UsersController@store')->name('user.store');
//    Route::post('users', [UsersController::class, 'store'])->name('user.store');

    Route::post('socials/{social_type}/authorizations',[AuthorizationsController::class, 'socialStore'])
        ->where('social_type', 'wechat')
        ->name('social.authorizations.store');
});




