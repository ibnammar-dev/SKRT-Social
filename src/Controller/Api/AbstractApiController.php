<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AbstractApiController extends AbstractController
{
    protected function json(mixed $data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        return new JsonResponse($data, $status, array_merge([
            'Content-Type' => 'application/json',
        ], $headers));
    }

    protected function createApiResponse($data = null, string $message = '', int $status = 200): JsonResponse
    {
        return $this->json([
            'success' => $status >= 200 && $status < 300,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    protected function createApiError(string $message, int $status = 400, array $errors = []): JsonResponse
    {
        return $this->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $status);
    }
}
