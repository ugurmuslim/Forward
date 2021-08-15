<?php

use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskDetailController;
use App\Http\Controllers\TaskDetailHistoryController;
use App\Http\Controllers\TaskTypeController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/task', [TaskController::class, 'index']);
Route::post('/task', [TaskController::class, 'create']);

Route::get('/taskDetail/{taskId}', [TaskDetailController::class, 'index']);
Route::post('/taskDetail/{taskId}', [TaskDetailController::class, 'create']);
Route::put('/taskDetail/{taskDetailId}', [TaskDetailController::class, 'update']);
Route::get('/taskDetail/nextStep/{taskId}', [TaskDetailController::class, 'nextStep']);

Route::get('/taskDetailHistory/{taskId?}', [TaskDetailHistoryController::class, 'index']);
Route::post('/taskDetailHistory/{taskDetailId}', [TaskDetailHistoryController::class, 'create']);

Route::get('/tasktype', [TaskTypeController::class,'index']);
Route::post('/tasktype', [TaskTypeController::class, 'create']);

Route::get('/currency', [CurrencyController::class,'index']);
Route::post('/currency', [CurrencyController::class, 'create']);
