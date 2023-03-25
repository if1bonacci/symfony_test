<?php

namespace App\Service\ExternalRequest;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RequestBuilder implements RequestBuilderInterface
{
    const REQUEST_GET = 'GET';
    const REQUEST_POST = 'POST';
    const REQUEST_PUT = 'PUT';
    const REQUEST_PATCH = 'PATCH';
    const REQUEST_DELETE = 'DELETE';

    #[Assert\Choice([self::REQUEST_GET, self::REQUEST_POST, self::REQUEST_DELETE, self::REQUEST_PUT, self::REQUEST_PATCH])]
    #[Assert\NotBlank]
    private string $method;

    #[Assert\NotBlank]
    private string $url;

    private ?OptionInterface $options = null;

    public function __construct(private HttpClientInterface $client, private readonly ValidatorInterface $validator)
    {
    }

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
        $this->validateRequest();
        $options = $this->getOptions() ? $this->getOptions()->getData() : [];

        return $this->getClient()->request(
            $this->getMethod(),
            $this->getUrl(),
            $options
        )->getContent();
    }

    private function validateRequest()
    {
        $errors = $this->validator->validate($this);
        if (count($errors) > 0) {
            throw new \HttpInvalidParamException($errors);
        }
    }
}
