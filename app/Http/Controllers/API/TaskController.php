<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks user.
     *
     * @return [string] message
     * @return [array] tasks
     */
    public function index()
    {
        $tasks = Auth::user()
            ->tasks()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'message' => 'Successfully get a listing of the tasks',
            'tasks' => $tasks
        ], 200);
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  [string] name
     * @return [string] message
     * @return [object] task
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $task = Auth::user()->tasks()->create([
            'name' => $request->get('name'),
            'done' => false
        ]);

        return response()->json([
            'message' => 'Successfully create a new task',
            'task' => $task
        ], 201);

    }

    /**
     * Update the specified task in storage.
     *
     * @param  [object] task
     * @param  [string] name
     * @return [string] message
     * @return [object] task
     */
    public function update(Request $request, $id)
    {
        $task = Auth::user()->tasks()->where('id', '=', $id)->first();

        if(!$task) {
            return response()->json([
                'message' => 'No find task in storage'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        if(!$task->isAuthor(Auth::user())) {
            return response()->json([
                'message' => 'No access rights to content'
            ], 403);
        }

        $task->update([
            'name' => $request->get('name')
        ]);

        return response()->json([
            'message' => 'Successfully update task',
            'task' => $task
        ], 200);
    }

    /**
     * Remove the specified task from storage.
     *
     * @param  [object] task
     * @return [string] message
     * @return [object] task
     */
    public function destroy($id)
    {
        $task = Auth::user()->tasks()->where('id', '=', $id)->first();

        if(!$task) {
            return response()->json([
                'message' => 'No find task in storage'
            ], 404);
        }

        if(!$task->isAuthor(Auth::user())) {
            return response()->json([
                'message' => 'No access rights to content'
            ], 403);
        }

        $task->delete();
        return response()->json([
            'message' => 'Successfully delete task',
            'task' => $task
        ], 200);
    }

    /**
     * Remove completed tasks from storage.
     *
     * @return [string] message
     * @return [array] tasks completed
     */
    public function destroyCompleted()
    {
        $tasks_completed = Auth::user()->tasks()->where('done', true)->delete();

        return response()->json([
            'message' => 'Successfully remove completed tasks',
            'tasks' => $tasks_completed
        ], 200);
    }

    /**
     * Set done the task from storage.
     *
     * @param  [object] task
     * @param  [boolean] done
     * @return [string] message
     * @return [object] task
     */
    public function check(Request $request, $id)
    {
        $task = Auth::user()->tasks()->where('id', '=', $id)->first();
        if(!$task) {
            return response()->json([
                'message' => 'No find task in storage'
            ], 404);
        }

        $request->validate([
            'done' => 'required|boolean',
        ]);

        $task->done = $request->get('done', false);
        $task->save();

        return response()->json([
            'message' => 'Successfully set done the task',
            'task' => $task
        ], 200);
    }
}
