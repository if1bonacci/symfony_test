<?php

namespace App\Service\PriceList;

use App\DTO\PricesListRequestInterface;
use App\Service\HistoricalData\HistoricalDataInterface;

class PriceListService implements PriceListInterface
{
    private const REQUIRED_FIELD = 'date';

    public function __construct(
        private readonly HistoricalDataInterface $historicalData,
    ) {}

    public function handleHistoricalData(PricesListRequestInterface $pricesListDto): array
    {
        $prices = $this->historicalData->getHistoricalData($pricesListDto);

        return $this->checkPeriods($prices, $pricesListDto);
    }

    private function checkPeriods(array $prices, PricesListRequestInterface $pricesListDto): array
    {
        $result = [];
        $startDate = $pricesListDto->getStartDateInt();
        $endDate = $pricesListDto->getEndDateInt();

        foreach ($prices as $price) {
            if ($startDate >= $price[self::REQUIRED_FIELD] && $price[self::REQUIRED_FIELD] <= $endDate) {
                $result[] = $price;
            }
        }

        return $result;
    }
}
