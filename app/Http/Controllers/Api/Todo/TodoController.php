<?php

namespace App\Http\Controllers\Api\Todo;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\Todo\StoreRequest;
use App\Http\Requests\Todo\UpdateTodoRequest;
use App\Services\TodoService;

class TodoController extends Controller
{

    public function __construct(protected TodoService $todoService)
    {
        //
    }
    
    public function index()
    {
        $userId = Auth::id();
        $response = $this->todoService->getUserTodos($userId);
        return response()->json($response->json(), $response->status());
    }

    public function store(StoreRequest $request)
    {
        $payload = [
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ];

        $response = $this->todoService->createTodo($payload);
        return response()->json($response->json(), $response->status());
    }


    public function update(UpdateTodoRequest $request, $id)
    {
        $payload = array_merge(
            $request->validated(),
            [
                'user_id' => Auth::id(),
            ]
        );
        $response = $this->todoService->updateTodo($id, $payload);

        return response()->json($response->json(), $response->status());
    }

    public function destroy($id)
    {
        $user = Auth::user();

        $response = Http::delete(config('services.todo.base_url') . "/todos/{$id}");

        return response()->json($response->json(), $response->status());
    }
}
