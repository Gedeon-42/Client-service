<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskService
{
    public function create(array $data, int $userId)
    {
        $data['user_id'] = $userId;
        $data['is_completed'] = false;
        return Task::create($data);
    }

    public function getAll(Request $request,$filter=[])
    {
        return $request->user()->tasks() ->when($filter, function ($query) use ($filter) {

                if ($filter === "today") {
                    $query->whereDate('due_date', Carbon::today());
                } elseif ($filter === "tomorrow") {

                    $query->whereDate('due_date', Carbon::tomorrow());
                } elseif ($filter === "this_week") {

                    $query->whereBetween('due_date', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek(),
                    ]);
                } elseif ($filter === "next_week") {
                    $query->whereBetween('due_date', [
                        Carbon::now()->addWeek()->startOfWeek(),
                        Carbon::now()->addWeek()->endOfWeek(),
                    ]);
                }
            })->when($filter, function ($query) use ($filter) {

                if ($filter['status'] === 'active') {
                    $query->where('is_completed', false);
                } elseif ($filter['status'] === 'completed') {
                    $query->where('is_completed', true);
                }
            })->when($filter,function($query) use($filter){
                if($filter['search']){
                    $query->where('title','like', '%'.$filter['search'] .'%');
                }
            })
           
            ->latest()
            ->paginate(5);
    }

    public function getStats(int $userId)
    {
        return [
            'total' => Task::where('user_id', $userId)->count(),
            'active' => Task::where('user_id', $userId)->where('is_completed', false),
            'completed' => Task::where('user_id', $userId)->where('is_completed', true)
        ];
    }

    public function view(Task $task)
    {
        return $task;
    }

    public function update(Task $task, array $data)
    {
        $task->update($data);
        return $task;
    }

    public function delete(Task $task)
    {
        $task->delete();
        return $task;
    }


    public function filterByDueDate(int $userId, string $filter)
    {
        return Task::where('user_id', $userId)
            ->when($filter, function ($query) use ($filter) {

                if ($filter === "today") {
                    $query->whereDate('due_date', Carbon::today());
                } elseif ($filter === "tomorrow") {

                    $query->whereDate('due_date', Carbon::tomorrow());
                } elseif ($filter === "this_week") {

                    $query->whereBetween('due_date', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek(),
                    ]);
                } elseif ($filter === "next_week") {
                    $query->whereBetween('due_date', [
                        Carbon::now()->addWeek()->startOfWeek(),
                        Carbon::now()->addWeek()->endOfWeek(),
                    ]);
                }
            })
            ->paginate(10);
    }


    public function filterByStatus(int $userId, string $status)
    {
        return Task::where('user_id', $userId)
            ->when($status, function ($query) use ($status) {

                if ($status === 'active') {
                    $query->where('is_completed', false);
                } elseif ($status === 'completed') {
                    $query->where('is_completed', true);
                }
            })
            ->paginate(10);
    }
}
