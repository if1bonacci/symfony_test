<?php

namespace App\Service\Symbol;

use App\Service\ExternalRequest\RequestBuilder;
use App\Service\ExternalRequest\RequestBuilderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class ExternalSymbolService implements SymbolInterface
{
    const REQUEST_FIELD = 'Symbol';
    const RESPONSE_FIELD = 'Company Name';

    public function __construct(
        private readonly RequestBuilderInterface $requestBuilder,
        private readonly ContainerBagInterface   $params,
    )
    {
    }

    public function getListOfSymbols(): array
    {
        $response = $this->requestBuilder
            ->setMethod(RequestBuilder::REQUEST_GET)
            ->setUrl($this->params->get('app.symbols_list_link'))
            ->send();

        return json_decode($response, true);
    }

    public function findSymbol(string $symbol): bool
    {
        $index = array_search(
            strtoupper($symbol),
            array_column($this->getListOfSymbols(), self::REQUEST_FIELD)
        );

        return $index === false;
    }

    public function findCompanyBySymbol(string $symbol): string
    {
        $data = $this->getListOfSymbols();
        $index = array_search(
            strtoupper($symbol),
            array_column($data, self::REQUEST_FIELD)
        );

        if ($index !== false) {
            return $data[$index][self::RESPONSE_FIELD];
        }

        return $symbol;
    }
}
