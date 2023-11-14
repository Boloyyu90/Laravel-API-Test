<?php

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

Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/contents', [App\Http\Controllers\Api\ContentController::class, 'index']);
    Route::post('/contents', [App\Http\Controllers\Api\ContentController::class, 'store']);
    Route::get('/contents/{id}', [App\Http\Controllers\Api\ContentController::class, 'show']);
    Route::put('/contents/{id}', [App\Http\Controllers\Api\ContentController::class, 'update']);
    Route::delete('/contents/{id}', [App\Http\Controllers\Api\ContentController::class, 'destroy']);

    Route::get('/activities', [App\Http\Controllers\Api\ActivitiesController::class, 'index']);
    Route::post('/activities', [App\Http\Controllers\Api\ActivitiesController::class, 'store']);
    // Route::get('/activities/{id}', [App\Http\Controllers\Api\ActivitiesController::class, 'show']);
    Route::get('/activities/{id}', [App\Http\Controllers\Api\ActivitiesController::class, 'customShowData']);
    Route::put('/activities/{id}', [App\Http\Controllers\Api\ActivitiesController::class, 'update']);
    Route::delete('/activities/{id}', [App\Http\Controllers\Api\ActivitiesController::class, 'destroy']);
});
