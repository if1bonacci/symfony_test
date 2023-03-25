<?php

namespace App\Service\HistoricalData;

use App\DTO\PricesListRequestInterface;

interface HistoricalDataInterface
{
    public function getHistoricalData(PricesListRequestInterface $dto): array;
}
