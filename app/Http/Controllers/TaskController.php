<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Http\Resources\StatsResource;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    public function __construct(protected TaskService $service) {}
    public function store(StoreTaskRequest $request)
    {
        $task = $this->service->create($request->validated(), $request->user()->id);
        return new TaskResource($task);
    }
    public function index(Request $request)
    {
        $tasks = $this->service->getAll($request, [
            'due_date' => $request->query('due_date'),
            'status' => $request->query('status'),
            'search' => $request->query('search')
        ]);
        $stats = $this->service->getStats($request->user()->id);
        return response()->json([
            'data' => TaskResource::collection($tasks),
            // 'stats' => new StatsResource($stats)
        ]);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return new TaskResource($this->service->view($task));
    }

    public function update(UpdateTaskRequest $updated, Task $task)
    {
        $this->authorize('update', $task);
        $updatedTask = $this->service->update($task, $updated->validated());
        return new TaskResource($updatedTask);
    }

    public function delete(Task $task)
    {
        $this->authorize('delete', $task);
        $this->service->delete($task);
        return response()->json([
            'message' => 'task deleted successfully'
        ]);
    }


    public function filterBydueDate(Request $request)
    {
        $tasks = $this->service->filterByDueDate(
            auth()->id(),
            $request->query('due_date')
        );

        return response()->json($tasks);
    }



    public function filterByStatus(Request $request)
    {

        $tasks = $this->service->filterByStatus(
            auth()->id(),
            $request->query('status')
        );

        return response()->json($tasks);
    }
}
