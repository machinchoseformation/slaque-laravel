<?php

namespace App\Api;

class JsonResponse
{
    private $data;
    private $errorMessage;
    private $status;
    private $statusCode;

    public function __construct(
        $data = [],
        string $errorMessage = "",
        string $status = "ok",
        int $statusCode = 200
    )
    {
        $this->data = $data;
        $this->errorMessage = $errorMessage;
        $this->status = $status;
        $this->statusCode = $statusCode;
    }

    public function send(){
        return response()->json([
            'error_message' => $this->errorMessage,
            'status' => $this->status,
            'data' => $this->data,
        ])->setStatusCode($this->statusCode);
    }
}