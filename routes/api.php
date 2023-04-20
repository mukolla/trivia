<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['api', 'auth'])->group(function () {
    Route::get('/players', [PlayerController::class, 'index']);
    Route::get('/players/{id}', [PlayerController::class, 'show']);
    Route::post('/players', [PlayerController::class, 'store']);
    Route::post('/players/{id}/answers', [PlayerController::class, 'answer']);
    Route::delete('/players/{id}', [PlayerController::class, 'delete']);
    Route::delete('/players/{id}/answers', [PlayerController::class, 'resetAnswers']);

    Route::post('/user/register', [UserController::class, 'register']);
});

Route::group(['middleware' => ['api'], 'prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('me', [AuthController::class, 'me']);
});
