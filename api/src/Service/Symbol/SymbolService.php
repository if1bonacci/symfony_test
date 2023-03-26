<?php

declare(strict_types=1);

namespace App\Service\Symbol;

use App\Service\ExternalRequest\RequestBuilderInterface;
use App\Service\ExternalRequest\SenderInterface;
use Symfony\Component\HttpFoundation\Request;

class SymbolService implements SymbolInterface
{
    private const REQUEST_FIELD = 'Symbol';

    private const RESPONSE_FIELD = 'Company Name';

    public function __construct(
        private readonly RequestBuilderInterface $requestBuilder,
        private readonly SenderInterface $sender,
        private readonly string $dataLink,
    ) {}

    public function getListOfSymbols(): array
    {
        $request = $this->requestBuilder
            ->setMethod(Request::METHOD_GET)
            ->setUrl($this->dataLink);
        $response = $this->sender->send($request);

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
