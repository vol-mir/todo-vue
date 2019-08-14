<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\UpdateTaskDoneRequest;
use App\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::all();
        return response()->json($tasks);
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \App\Http\Requests\StoreTaskRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->all());
        return response()->json($task);
    }

    /**
     * Update the specified task in storage.
     *
     * @param  \App\Http\Requests\UpdateTaskRequest $request
     * @param  Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->all());
        return response()->json($task);
    }

    /**
     * Remove the specified task from storage.
     *
     * @param  Task $task
     * @return \Illuminate\Http\Response
     */

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json($task);
    }

    /**
     * Remove completed tasks from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy_completed()
    {
        $task_completed = Task::where('done', true)->delete();
        return response()->json($task_completed);
    }

    /**
     * Set done the task from storage.
     *
     * @param  \App\Http\Requests\UpdateTaskDoneRequest $request
     * @param  Task  $task
     * @return \Illuminate\Http\Response
     */
    public function check(UpdateTaskDoneRequest $request, Task $task)
    {
        $task->done = $request->get('done', false);
        $task->save();
        return response()->json($task);
    }
}
