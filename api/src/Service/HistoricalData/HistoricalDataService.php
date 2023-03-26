<?php

namespace App\Service\HistoricalData;

use App\DTO\Price;
use App\DTO\PricesListRequestInterface;
use App\Service\ExternalRequest\OptionInterface;
use App\Service\ExternalRequest\RequestBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HistoricalDataService implements HistoricalDataInterface
{
    private const REQUEST_FIELD = 'symbol';

    public function __construct(
        private readonly HttpClientInterface     $historicalClient,
        private readonly SerializerInterface     $dtoSerializer,
        private readonly RequestBuilderInterface $requestBuilder,
        private readonly OptionInterface         $option,
        private readonly string                  $dataLink,
    ) {}

    public function getHistoricalData(PricesListRequestInterface $dto): array
    {
        $response = $this->requestBuilder
            ->setClient($this->historicalClient)
            ->setMethod(Request::METHOD_GET)
            ->setUrl($this->dataLink)
            ->setOptions($this->option->setQueryParams([
                self::REQUEST_FIELD => $dto->getSymbol()
            ]))
            ->send();

        $priceDto = $this->dtoSerializer->deserialize($response, Price::class, JsonEncoder::FORMAT);

        return $priceDto->getPrices();
    }
}
