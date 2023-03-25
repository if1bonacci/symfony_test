<?php

namespace App\Service\PriceList;

interface PriceListInterface
{
    public function handleHistoricalData(string $content): array;

}
