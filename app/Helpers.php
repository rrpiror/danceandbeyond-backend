<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

if (!function_exists('apiResponse')) {
    function apiResponse($result = true, $data = null, $message = null, $errorCode = null, $statusCode = 200): \Illuminate\Http\JsonResponse
    {
        if ($result) {
            return response()->json([
                'success' => $result,
                'data' => $data,
                'error' => null
            ], $statusCode);
        } else {
            return response()->json([
                'success' => $result,
                'data' => null,
                'error' => [
                    'code' => $errorCode,
                    'message' => $message,
                    'data' => $data
                ]
            ], $statusCode);
        }
    }
}
