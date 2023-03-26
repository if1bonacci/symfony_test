<?php

namespace App\Service\Symbol;

use App\Service\ExternalRequest\RequestBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

class SymbolService implements SymbolInterface
{
    private const REQUEST_FIELD = 'Symbol';
    const RESPONSE_FIELD = 'Company Name';

    public function __construct(
        private readonly RequestBuilderInterface $requestBuilder,
        private readonly string $dataLink,
    ){}

    public function getListOfSymbols(): array
    {
        $response = $this->requestBuilder
            ->setMethod(Request::METHOD_GET)
            ->setUrl($this->dataLink)
            ->send();

        return \json_decode($response, true);
    }

    public function findSymbol(string $symbol): bool
    {
        $index = \array_search(
            \strtoupper($symbol),
            \array_column($this->getListOfSymbols(), self::REQUEST_FIELD)
        );

        return $index === false;
    }

    public function findCompanyBySymbol(string $symbol): string
    {
        $data = $this->getListOfSymbols();
        $index = \array_search(
            \strtoupper($symbol),
            \array_column($data, self::REQUEST_FIELD)
        );

        if ($index !== false) {
            return $data[$index][self::RESPONSE_FIELD];
        }

        return $symbol;
    }
}