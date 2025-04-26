<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Jobs\ProcessIntensiveTaskJob;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function submitTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $task = Task::create([
            'input_data' => ['text' => $request->text],
            'status' => 'pending',
        ]);

        ProcessIntensiveTaskJob::dispatch($task);

        return response()->json([
            'message' => 'Task submitted successfully.',
            'task_id' => $task->id,
            'status' => $task->status,
        ], 202); // 202 Accepted for async task
    }

    public function getStatus($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        return response()->json([
            'task_id' => $task->id,
            'status' => $task->status,
        ]);
    }

    public function getResult($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        if ($task->status === 'completed') {
            return response()->json([
                'task_id' => $task->id,
                'result' => $task->result,
            ]);
        }

        if ($task->status === 'failed') {
            return response()->json([
                'task_id' => $task->id,
                'error' => $task->error_message,
            ], 500);
        }

        return response()->json([
            'message' => 'Task is still processing or pending',
            'status' => $task->status
        ], 202); // HTTP 202 Accepted
    }
}
