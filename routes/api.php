<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\PasswordResetController;


Route::prefix('v1')->group(function () {

    Route::post('/signin', [AuthController::class, 'signin'])->name('auth.signin');
    Route::post('signup', [AuthController::class, 'signup'])->name('auth.signup');
    Route::get('signup/activate/{token}', [AuthController::class, 'activate'])->name('auth.activate');

    Route::group(['middleware' => ['auth:api']], function() {
        Route::delete('/tasks/destroy/completed', [TaskController::class, 'destroyCompleted'])->name('tasks.destroy.completed');
        Route::patch('/tasks/{id}/check', [TaskController::class, 'check'])->name('tasks.check');
        Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
        Route::patch('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
        Route::apiResource('/tasks', 'API\TaskController')->except('show','destroy','update');

        Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('/user', [AuthController::class, 'user'])->name('auth.user');
        Route::patch('/user/update', [AuthController::class, 'update'])->name('auth.update');
        Route::post('/user/update/avatar', [AuthController::class, 'updateAvatar'])->name('auth.update.avatar');
        Route::patch('/user/update/password', [AuthController::class, 'updatePassword'])->name('auth.update.password');
    });

    Route::group(['prefix' => 'password'
    ], function () {
        Route::post('create', [PasswordResetController::class, 'create'])->name('password.create');
        Route::post('reset', [PasswordResetController::class, 'reset'])->name('password.reset');
        Route::get('find/{token}', [PasswordResetController::class, 'find'])->name('password.find');
    });

});
