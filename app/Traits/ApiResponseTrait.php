<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    /**
     * Send a success JSON response
     *
     * @param  mixed   $data
     * @param  string|null  $message
     * @param  int     $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data = null, string $message = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'status'  => true, 
            'message' => $message ?? 'Request successful',
            'data'    => $data,
        ], $code);
    }

    /**
     * Send an error JSON response
     *
     * @param  string|null  $message
     * @param  int     $code
     * @param  array|null  $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse(string $message = null, int $code = 400, array $errors = null): JsonResponse
    {
        return response()->json([
            'status'  => false, 
            'message' => $message ?? 'Something went wrong',
            'errors'  => $errors,
        ], $code);
    }

     
    /**
     * Send an unauthorized response
     */
    protected function unauthorizedResponse(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->errorResponse($message, 401);
    }

    /**
     * Send a not found response
     */
    protected function notFoundResponse(string $message = 'Resource not found'): JsonResponse
    {
        return $this->errorResponse($message, 404);
    }

    /**
     * Send a validation error response
     */
    protected function validationErrorResponse($errors, string $message = 'Validation failed'): JsonResponse
    {
        return $this->errorResponse($message, 422, $errors);
    }  
}
