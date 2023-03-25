<?php

namespace App\Service\ExternalRequest;

class Options implements OptionInterface
{
    private ?array $headers = [];
    private ?array $query = [];

    public function setHeaders(?array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setQueryParams(?array $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function getQueryParams(): array
    {
        return $this->query;
    }

    public function getData(): array
    {
        return [
            'query' => $this->getQueryParams(),
            'headers' => $this->getHeaders()
        ];
    }
}
