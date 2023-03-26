<?php

namespace App\Service\ExternalRequest;

interface OptionInterface
{
    public function setHeaders(?array $headers): self;

    public function getHeaders(): array;

    public function setQueryParams(?array $query): self;

    public function getQueryParams(): array;

    public function getData(): array;
}
