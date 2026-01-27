<?php

use Illuminate\Support\Facades\Http;

if (!function_exists('clientRequest')) {
    function clientRequest() {
        Http::config('services.todo.base_url') . '/todos';
    }
}
