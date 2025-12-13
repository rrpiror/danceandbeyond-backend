<?php

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

if (!function_exists('successResponse')) {
    function successResponse($data = null, $message = null, $statusCode = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
            'error' => null
        ], $statusCode);
    }
}

if (!function_exists('errorResponse')) {
    function errorResponse($message = null, $errors = null, $errorCode = null, $statusCode = 500): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => [
                'code' => $errorCode,
                'message' => $message,
                'errors' => $errors
            ]
        ], $statusCode);
    }
}

