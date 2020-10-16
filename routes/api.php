<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
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

Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);

Route::apiResource('products', ProductController::class)->only(['index']);
Route::apiResource('carts', CartController::class)
    ->except(['show'])
    ->middleware('auth:api');
Route::apiResource('orders', CartController::class)
    ->middleware('auth:api');
