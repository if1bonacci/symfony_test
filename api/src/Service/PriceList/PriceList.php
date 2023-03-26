<?php

namespace App\Service\PriceList;

use App\DTO\PricesListRequestInterface;

abstract class PriceList implements PriceListInterface
{
    protected PricesListRequestInterface $pricesListDto;

    public function getDto(): PricesListRequestInterface
    {
        return $this->pricesListDto;
    }
}
