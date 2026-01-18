<?php

namespace App\Http\Controllers\Api\Todo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\Todo\StoreRequest;

class TodoController extends Controller
{
    //

    public function store(StoreRequest $request)
    {
        $payload = [
            'title' => $request->title,
            'description' => $request->description ,
            'user_id' => Auth::id(),
        ];

        $response = Http::post(config('services.todo.url').'/todos', $payload);

        return response()->json(
            $response->json(),
        );
    }
}
