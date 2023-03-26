<?php

namespace App\Service\ExternalRequest;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class RequestBuilder implements RequestBuilderInterface
{
    private string $method;

    private string $url;

    private ?OptionInterface $options = null;

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function setOptions(?OptionInterface $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getOptions(): ?OptionInterface
    {
        return $this->options;
    }
}
