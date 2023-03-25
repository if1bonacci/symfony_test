<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiException extends HttpException
{
    private ApiExceptionDataInterface $exceptionData;

    public function __construct(ApiExceptionDataInterface $exceptionData)
    {
        $statusCode = $exceptionData->getStatusCode();
        $message = $exceptionData->getType();

        parent::__construct($statusCode, $message);
        $this->exceptionData = $exceptionData;
    }

    public function getExceptionData(): ApiExceptionDataInterface
    {
        return $this->exceptionData;
    }
}
