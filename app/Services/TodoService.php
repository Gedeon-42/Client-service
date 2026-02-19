<?php

namespace App\Services;

use App\Exceptions\GeneralException;
use Illuminate\Support\Facades\Http;

class TodoService
{
    protected $baseUrl;
    public function getUserTodos($userId)
    {
        $response = Http::get(
            config('services.todo.base_url') . '/todos',
            [
                'user_id' => $userId,
            ]
        );

        if ($response->failed()) {
            throw new GeneralException($response->json('message'), $response->status());
        }
    }

    public function createTodo($payload)
    {
        $response = Http::acceptJson()->post(
            config('services.todo.base_url') . '/todos',
            $payload
        );

        if ($response->failed()) {
            //  dd($response->json('message'));
            throw new GeneralException($response->json('message'), $response->status());
        }
    }
    public function updateTodo($id, $payload)
    {
        $response = Http::put("{$this->baseUrl}/todos/{$id}", $payload);

        if ($response->failed()) {
            throw new \Exception('Failed to update todo');
        }
    }
}
