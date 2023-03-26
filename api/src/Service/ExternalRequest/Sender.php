<?php

namespace App\Service\ExternalRequest;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class Sender implements SenderInterface
{
    private HttpClientInterface $client;

    public function setClient(HttpClientInterface $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function send(RequestBuilderInterface $requestBuilder): string
    {
        $options = $requestBuilder->getOptions() ? $requestBuilder->getOptions()->getData() : [];
        $method = $requestBuilder->getMethod();
        $url = $requestBuilder->getUrl();

        return $this->client->request($method, $url, $options)->getContent();
    }
}
