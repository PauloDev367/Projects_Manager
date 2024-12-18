<?php

use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\ColumnsController;
use App\Http\Controllers\V1\ProjectsController;
use App\Http\Controllers\V1\TaskToDoController;
use App\Http\Controllers\V1\TicketController;
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
    'prefix' => 'v1/auth/'
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
    });
    Route::group([
        "middleware" => "auth:api",
        "prefix" => "columns"
    ], function () {
        Route::post('', [ColumnsController::class, 'create']);
        Route::get('project/{id}', [ColumnsController::class, 'getAll']);
        Route::delete('{id}', [ColumnsController::class, 'delete']);
        Route::put('{id}', [ColumnsController::class, 'update']);
        Route::put('{id}', [ColumnsController::class, 'update']);
        Route::patch('project/{id}/positions', [ColumnsController::class, "updateColumnsPosition"]);
    });
    Route::group([
        "middleware" => "auth:api",
        "prefix" => "tasks"
    ], function () {
        Route::post('', [TaskToDoController::class, 'create']);
        Route::get('{id}', [TaskToDoController::class, 'getOne']);
        Route::get('column/{id}', [TaskToDoController::class, 'getAll']);
        Route::patch('column/{id}/positions', [TaskToDoController::class, 'updateTasksPosition']);
        Route::delete('{id}', [TaskToDoController::class, 'delete']);
        Route::put('{id}', [TaskToDoController::class, 'update']);
        Route::put('{id}/tickets', [TaskToDoController::class, 'setTickets']);
    });

    Route::group([
        "middleware" => "auth:api",
        "prefix" => "tickets"
    ], function () {
        Route::post('', [TicketController::class, 'create']);
        Route::get('{id}', [TicketController::class, 'getOne']);
        Route::get('', [TicketController::class, 'getAll']);
        Route::delete('{id}', [TicketController::class, 'delete']);
        Route::put('{id}', [TicketController::class, 'update']);
    });
});
