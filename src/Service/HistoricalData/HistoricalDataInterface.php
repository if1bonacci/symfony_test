<?php

namespace App\Service\HistoricalData;

interface HistoricalDataInterface
{
    public function getHistoricalData(array $queryParams): array;
}
