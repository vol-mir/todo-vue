<?php

use Illuminate\Http\Request;

Route::group(['prefix' => 'v1'], function () {
    Route::apiResource('/tasks', 'API\TaskController');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
