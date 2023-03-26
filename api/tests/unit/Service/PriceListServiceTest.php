<?php

declare(strict_types=1);

namespace App\Tests\unit\Service;

use App\DTO\PricesListRequestInterface;
use App\Service\HistoricalData\HistoricalDataInterface;
use App\Service\PriceList\PriceListService;
use PHPUnit\Framework\TestCase;

class PriceListServiceTest extends TestCase
{
    const RESPONSE = [
        [
            "date" => 1679534600,
            "open" => 101.9800033569336,
            "high" => 105.95999908447266,
            "low" => 101.86000061035156,
            "close" => 105.83999633789062,
            "volume" => 33077400,
        ],
        [
            "date" => 1388516401,
            "open" => 101.05999755859375,
            "high" => 102.58000183105469,
            "low" => 100.79000091552734,
            "close" => 101.93000030517578,
            "volume" => 26033900,
        ],
        [
            "date" => 1679664600,
            "open" => 101.05999755859375,
            "high" => 102.58000183105469,
            "low" => 100.79000091552734,
            "close" => 101.93000030517578,
            "volume" => 26033900,
        ],
    ];

    public function testHandleHistoricalData()
    {
        $mockPriceReq = $this->createMock(PricesListRequestInterface::class);
        $mockPriceReq
            ->expects(self::once())
            ->method('getStartDateInt')
            ->willReturn(1679634600);
        $mockPriceReq
            ->expects(self::once())
            ->method('getEndDateInt')
            ->willReturn(1679664600);

        $mockHistorical = $this->createMock(HistoricalDataInterface::class);
        $mockHistorical
            ->expects(self::once())
            ->method('getHistoricalData')
            ->willReturn(self::RESPONSE);

        $priceListService = new PriceListService($mockHistorical);

        $response = $priceListService->handleHistoricalData($mockPriceReq);

        $this->assertEquals(2, count($response));
    }
}
