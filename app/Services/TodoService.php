<?php 
namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class TodoService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.todo.base_url');
    }

    public function getTodos($userId)
    {
        $response = Http::get("{$this->baseUrl}/todos", [
            'user_id' => $userId,
        ]);

        return $response;
    }

    public function createTodo($payload)
    {
         $response = Http::post(
            config('services.todo.base_url') . '/todos',
            $payload
        );

        if ($response->failed()) {
            throw new \Exception('Service B error on creating todo');

            return response()->json([
                'message' => 'Todo service error',
                'error' => $response->json()
            ], 502);
        }
        return $response;
    }
    public function updateTodo($id, $payload)
    {
        $response = Http::put("{$this->baseUrl}/todos/{$id}", $payload);

        if ($response->failed()) {
           throw new \Exception('Failed to update todo');
            return response()->json([
                'message' => 'Todo service error',
                'error' => $response->json()
            ], 502);
        }
        return $response;
    }
}