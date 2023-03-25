<?php

namespace App\Exception;

class ApiExceptionData implements ApiExceptionDataInterface
{
    public function __construct(protected int $statusCode, protected string $type){}

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function toArray(): array
    {
        return [
            'error' => $this->type
        ];
    }
}
