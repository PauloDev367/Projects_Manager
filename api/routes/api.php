<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\ProjectsController;
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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});


Route::group(["prefix" => "v1"], function () {
    Route::group([
        "middleware" => "auth:api",
        "prefix" => "projects"
    ], function () {
        Route::post('', [ProjectsController::class, "create"]);
        Route::get('{id}', [ProjectsController::class, "getOne"]);
        Route::get('', [ProjectsController::class, "getAll"]);
        Route::delete('{id}', [ProjectsController::class, "delete"]);
        Route::put('{id}', [ProjectsController::class, "update"]);
    });
});
