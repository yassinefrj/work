<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\GroupParticipationController;
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

/** API FOR TASKS */
Route::get('/tasks/personList/{id}', [TaskController::class, 'getTasksById']);


/** API FOR GROUPS */
Route::get('/groups', [GroupController::class, 'getGroupApi']);
Route::get('/groupspart', [GroupParticipationController::class, 'getAllParticipantsGroupApi']);
Route::get('/groups/{id}', [GroupParticipationController::class, 'registerToGroup']);
Route::get('/groups/user/{id}', [GroupParticipationController::class, 'getGroupsByUserId']);
Route::get('/groupParticipation', [GroupParticipationController::class, 'getAllParticipantsGroup']);
Route::get('/waiting-count', [GroupParticipationController::class, 'getWaitingCount']);
Route::post('/groups/unregister', [GroupParticipationController::class, 'unregisterToGroup']);
Route::post('updateUserParticipation', [GroupParticipationController::class, 'updateUserParticipation']);
Route::post('removeUserParticipation', [GroupParticipationController::class, 'rejectUserParticipation']);
