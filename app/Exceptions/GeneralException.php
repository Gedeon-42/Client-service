<?php

namespace App\Exceptions;

use Exception;

class GeneralException extends Exception
{
    protected $status;
    public function __construct(string $message = "", int $statusCode)
    {
        parent::__construct($message);
        $this->status = $statusCode;
    }

    public function render()
    {
        return response()->json([
            'message' => $this->getMessage(),
            'status' => $this->status,
        ], $this->status);
    }
}
