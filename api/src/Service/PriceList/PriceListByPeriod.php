<?php

namespace App\Service\PriceList;

use App\DTO\PricesListRequestInterface;

class PriceListByPeriod implements PriceListInterface
{
    const REQUIRED_FIELD = 'date';
    protected PricesListRequestInterface $pricesListDto;

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
        $startDate = strtotime($this->pricesListDto->getStartDate()->format(DATE_ATOM));
        $endDate = strtotime($this->pricesListDto->getEndDate()->format(DATE_ATOM));

        foreach ($prices as $price) {
            if ($price[self::REQUIRED_FIELD] >= $startDate && $price[self::REQUIRED_FIELD] <= $endDate) {
                $result[] = $price;
            }
        }

        return $result;
    }

    public function getDto(): PricesListRequestInterface
    {
        return $this->pricesListDto;
    }
}
