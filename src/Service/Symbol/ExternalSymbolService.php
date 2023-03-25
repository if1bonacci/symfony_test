<?php

namespace App\Service\Symbol;

use App\Service\ExternalRequest\RequestBuilder;
use App\Service\ExternalRequest\RequestBuilderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class ExternalSymbolService implements SymbolsList
{
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
            array_column($this->getListOfSymbols(), 'Symbol')
        );

        return $index === false;
    }
}
