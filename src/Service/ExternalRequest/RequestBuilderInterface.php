<?php

namespace App\Service\ExternalRequest;

use Symfony\Contracts\HttpClient\HttpClientInterface;

interface RequestBuilderInterface
{
    public function setClient(HttpClientInterface $client): self;

    public function setMethod(string $method): self;

    public function setOptions(?OptionInterface $options): self;

    public function setUrl(string $url): self;

    public function getUrl(): string;

    public function getMethod(): string;

    public function getOptions(): ?OptionInterface;

    public function getClient(): HttpClientInterface;

    public function send(): string;
}
