<?php

namespace App\EventListener;

use App\Exception\ApiException;
use App\Exception\ApiExceptionData;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ApiException) {
            $exceptionData = $exception->getExceptionData();
        } else {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $exceptionData = new ApiExceptionData($statusCode, $exception->getMessage());
        }

        $response = new JsonResponse($exceptionData->toArray());
        $event->setResponse($response);
    }
}
