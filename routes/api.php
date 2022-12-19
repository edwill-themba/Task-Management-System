<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

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

// protected routes
Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout'])->name('user.logout');
    // gets all user created tasks for subordinates users
    Route::get('user_tasks', [TaskController::class, 'user_task'])->name('user.tasks');
    // gets all user assigned tasks by a supervisor
    Route::get('assigned_tasks', [TaskController::class, 'my_assigned_tasks'])->name('user_assigned.tasks');
    // creates user own tasks
    Route::post('task', [TaskController::class, 'store'])->name('user.store');
    // gets list of users for in order to create a task for the use
    Route::get('user_lists', [TaskController::class, 'my_users'])->name('users.list');
     // check users schedule for supervisors to see available dates for users
    Route::get('user_task/{id}', [TaskController::class, 'get_users_task'])->name('supervisor.user.tasks');
    // supervisor create task for a specific user
    Route::post('user_task', [TaskController::class, 'store_user_task'])->name('supervisor.store.task');
    // updates user type
    Route::patch('task/{id}', [TaskController::class, 'update'])->name('user.task.update');
    // updates task created by user
    Route::patch('user_supervisor_task/{id}', [TaskController::class, 'update_supervisor_task'])->name('supervisor.user.task.update');
    // delete user task
    Route::delete('task/{id}', [TaskController::class, 'destroy'])->name('user.task.destroy');
    // delete user task created by supervisor
    Route::delete('user_supervisor_task/{id}', [TaskController::class, 'destroy_supervisor_user'])->name('supervisor.user.task.destroy');

});

// public routes
Route::post('register', [AuthController::class, 'register'])->name('user.register');

Route::post('login', [AuthController::class, 'login'])->name('user.login');

Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');

Route::get('task/search/{name}', [TaskController::class, 'search'])->name('tasks.search');