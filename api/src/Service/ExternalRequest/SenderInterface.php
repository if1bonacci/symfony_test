<?php

namespace App\Service\ExternalRequest;

use Symfony\Contracts\HttpClient\HttpClientInterface;

interface SenderInterface
{
    public function send(RequestBuilderInterface $requestBuilder): string;

    public function setClient(HttpClientInterface $client): self;
}
