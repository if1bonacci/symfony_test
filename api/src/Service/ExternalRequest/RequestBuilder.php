<?php

namespace App\Service\ExternalRequest;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class RequestBuilder implements RequestBuilderInterface
{
    private string $method;

    private string $url;

    private HttpClientInterface $client;

    private ?OptionInterface $options = null;

    public function setClient(HttpClientInterface $client): self
    {
        $this->client = $client;

        return $this;
    }

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

    public function getClient(): HttpClientInterface
    {
        return $this->client;
    }

    public function send(): string
    {
        $options = $this->getOptions() ? $this->getOptions()->getData() : [];

        return $this->getClient()->request(
            $this->getMethod(),
            $this->getUrl(),
            $options
        )->getContent();
    }
}
