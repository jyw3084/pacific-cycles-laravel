<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;
use App\Http\Controllers\AJAXController;

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

Route::post('makeApi', [APIController::class, 'makeApi']);
Route::post('register', [APIController::class, 'register']);
Route::post('login', [APIController::class, 'login']);
Route::post('getMemberData', [APIController::class, 'getMemberData']);
Route::post('saveMemberData', [APIController::class, 'saveMemberData']);
Route::post('sendSms', [APIController::class, 'sendSms']);
Route::post('ajax', [AJAXController::class, 'ajax']);



