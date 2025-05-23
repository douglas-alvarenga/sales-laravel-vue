<?php

namespace App\Support\Helpers;

class ApiHelper
{

    public static function responseSuccess(array $data = [], int $status = 200)
    {
        return response()->json(
            [
                'success' => true,
                'data' => $data,
            ],
            $status
        );
    }

    public static function responseUnsuccess(string $message = '', array $data = [], int $status = 422)
    {
        return response()->json(
            [
                'success' => false,
                'message' => $message,
                'data' => $data,
            ],
            $status
        );
    }
}
