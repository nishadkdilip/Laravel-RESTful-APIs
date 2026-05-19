<?php

namespace App\services;

class ResponseService
{
    public function success( string $message,  $data, int $code = null )
    {
        return response()->json([
            "status" => true,
            "message" => $message,
            "data" => $data,
        ], $code ?? 200);
    }
    public function error( string $message, string|array $data, int $code = null)
    {
        return response()->json([
            "status" =>false,
            "message" => $message,
            "data" => $data,
        ], $code ?? 500);
    }

}
