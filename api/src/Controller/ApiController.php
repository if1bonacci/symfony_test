<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

abstract class ApiController
{
    public function httpOk(mixed $data): JsonResponse
    {
        return new JsonResponse(
           $data,
            JsonResponse::HTTP_OK
        );
    }
}
