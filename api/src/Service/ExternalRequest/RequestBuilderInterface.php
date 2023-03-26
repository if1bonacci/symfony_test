<?php

namespace App\Service\ExternalRequest;

interface RequestBuilderInterface
{
    public function setMethod(string $method): self;

    public function getMethod(): string;

    public function setOptions(?OptionInterface $options): self;

    public function getOptions(): ?OptionInterface;

    public function setUrl(string $url): self;

    public function getUrl(): string;
}
