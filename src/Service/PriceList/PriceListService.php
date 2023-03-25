<?php

namespace App\Service\PriceList;

use App\DTO\PricesListRequest;
use App\Service\HistoricalData\HistoricalDataInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class PriceListService implements PriceListInterface
{
    public function __construct(
        private readonly SerializerInterface $dtoSerializer,
        private readonly HistoricalDataInterface $historicalData,
    ){}
    public function handleHistoricalData(string $content): array
    {
        $pricesListDto = $this->dtoSerializer->deserialize($content, PricesListRequest::class, JsonEncoder::FORMAT);

        return $this->historicalData->getHistoricalData($pricesListDto);
    }
}
