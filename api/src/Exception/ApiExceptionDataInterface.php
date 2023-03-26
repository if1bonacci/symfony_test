<?php

namespace App\Exception;

interface ApiExceptionDataInterface
{
    public function getStatusCode(): int;

    public function getType(): string;

    public function toArray(): array;
}
