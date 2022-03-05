<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\LoteController;
use App\Http\Controllers\api\AutorizacionController;
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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/token', [AuthController::class, 'token']);


Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/autorizacion', [AutorizacionController::class, 'register']);
    Route::get('/autorizacion/{id}', [AutorizacionController::class, 'index']);
    Route::post('/register-lote', [LoteController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);

});

