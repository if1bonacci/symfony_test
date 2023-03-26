<?php

namespace App\Service\PriceList;

class PriceListByPeriod extends PriceList
{
    const REQUIRED_FIELD = 'date';
    const END_OF_DAY = 86399; //add 23:59:59

    public function __construct(private readonly PriceListInterface $priceList)
    {
    }

    public function handleHistoricalData(string $content): array
    {
        $prices = $this->priceList->handleHistoricalData($content);
        $this->pricesListDto = $this->priceList->getDto();

        return $this->checkPeriods($prices);
    }

    private function checkPeriods(array $prices): array
    {
        $result = [];
        $startDate = strtotime($this->pricesListDto->getStartDate()->format(DATE_ATOM)) + self::END_OF_DAY;
        $endDate = strtotime($this->pricesListDto->getEndDate()->format(DATE_ATOM)) + self::END_OF_DAY;

        foreach ($prices as $price) {
            if ($startDate >= $price[self::REQUIRED_FIELD] && $price[self::REQUIRED_FIELD] <= $endDate) {
                $result[] = $price;
            }
        }

        return $result;
    }
}
