<?php

namespace App\Http\Controllers;

use App\Exceptions\GeneralException;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    //
    public function index(){

    }

    public function filterTask(Request $request)
    {
        $tasks = Task::where('user_id', auth()->id())
            ->when($request->filled('status'), function ($query) use ($request) {

                match ($request->status) {
                    'active' => $query->where('is_completed', false),
                    'completed' => $query->where('is_completed', true),
                    default => null,
                };
            })
            ->get();

        return TaskResource::collection($tasks);
    }



    public function filterBydueDate(Request $request)
    {
        $tasks = Task::where('user_id', auth()->id())
            ->when($request->query('due_date'), function ($query, $filter) {

                match ($filter) {
                    'today' => $query->whereDate('due_date', Carbon::today()),

                    'tomorrow' => $query->whereDate('due_date', Carbon::tomorrow()),

                    'this_week' => $query->whereBetween('due_date', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek(),
                    ]),

                    'next_week' => $query->whereBetween('due_date', [
                        Carbon::now()->addWeek()->startOfWeek(),
                        Carbon::now()->addWeek()->endOfWeek(),
                    ]),

                    default => null,
                };
            })
            ->get();

        return response()->json($tasks);
    }

    public function getStatistics(Request $request)
    {

        $totalActiveTask = Task::where('user_id', auth()->id())
        ->where('is_completed',false)->count();
        $totalCompletedTask = Task::where('user_id', auth()->id())
        ->where('is_completed',true)->count();
        return response()->json([
            'totalActiveTask'=>$totalActiveTask,
            'totalCompletedTask'=>$totalCompletedTask
        ]);
    }

    public function store(StoreTaskRequest $request)
    {

        $user = Auth::user();
        $data = $request->validated();
        $task = Task::create([
            'title' => $data['title'],
            'user_id' => $user->id,
            'description' => $data['description'],
            'due_date' => $data['due_date']
        ]);

        return new TaskResource($task);
    }

    public function markCompleted(Task $task)
    {
        if ($task->is_completed == Task::TASK_COMPLETED) {
            throw new GeneralException('Task is always completed', 401);
        }
        $task->update([
            'is_completed' => Task::TASK_COMPLETED,
            'completed_at' => now()
        ]);
        return response()->json([
            'task' => $task
        ]);
    }

    public function show(){
        
    }
}
