<?php

namespace App\Service\PriceList;

use App\DTO\PricesListRequestInterface;

interface PriceListInterface
{
    public function handleHistoricalData(PricesListRequestInterface $pricesListDto): array;

}
