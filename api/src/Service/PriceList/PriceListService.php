<?php

namespace App\Service\PriceList;

use App\DTO\PricesListRequest;
use App\DTO\PricesListRequestInterface;
use App\Service\HistoricalData\HistoricalDataInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class PriceListService implements PriceListInterface
{
    protected PricesListRequestInterface $pricesListDto;

    public function __construct(
        private readonly SerializerInterface     $dtoSerializer,
        private readonly HistoricalDataInterface $historicalData,
    )
    {
    }

    public function handleHistoricalData(string $content): array
    {
        $pricesListDto = $this->dtoSerializer->deserialize($content, PricesListRequest::class, JsonEncoder::FORMAT);
        $this->pricesListDto = $pricesListDto;

        return $this->historicalData->getHistoricalData($pricesListDto);
    }

    public function getDto(): PricesListRequestInterface
    {
        return $this->pricesListDto;
    }
}
