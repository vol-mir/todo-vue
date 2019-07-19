<?php

use Illuminate\Http\Request;

Route::group(['prefix' => 'v1'], function () {
    Route::delete('/tasks/destroy_completed', ['uses'=>'API\TaskController@destroy_completed', 'as'=>'tasks.destroy_completed']);
    Route::patch('/tasks/{task}/check', ['uses'=>'API\TaskController@check', 'as'=>'tasks.check']);
    Route::apiResource('/tasks', 'API\TaskController')->except('show');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
