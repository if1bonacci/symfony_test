<?php

namespace App\Tests\unit\Service;

use App\DTO\PricesListRequest;
use App\DTO\PricesListRequestInterface;
use App\Message\MessageInterface;
use App\Service\HistoricalData\HistoricalDataInterface;
use App\Service\PriceList\PriceListService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
class PriceListServiceTest extends TestCase
{
    const LIST_OF_PRICES = '{"prices":[{"date":1679407629,"open":101.9800033569336,"high":103.4800033569336,"low":101.86000061035156,"close":103.03500366210938,"volume":3665412,"adjclose":103.03500366210938},{"date":1679319000,"open":101.05999755859375,"high":102.58000183105469,"low":100.79000091552734,"close":101.93000030517578,"volume":26015800,"adjclose":101.93000030517578}]}';

    const CONTEXT = '
        {
            "symbol": "GOOG",
            "startDate": "2023-03-01",
            "endDate": "2023-03-25",
            "email": "test@example.com"
        }
    ';

    const EMAIL = 'test@example.com';
    const SYMBOL = 'GOOG';

    const RESPONSE = [
        [
            "date" => 1679405400,
            "open" => 101.9800033569336,
            "high" => 105.95999908447266,
            "low" => 101.86000061035156,
            "close" => 105.83999633789062,
            "volume" => 33077400,
        ],
        [
            "date" => 1679319000,
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
        $mockSerializer = $this->createMock(SerializerInterface::class);
        $mockSerializer
            ->expects(self::once())
            ->method('deserialize')
            ->with(self::CONTEXT, PricesListRequest::class, JsonEncoder::FORMAT)
            ->willReturn($mockPriceReq);

        $mockHistorical = $this->createMock(HistoricalDataInterface::class);
        $mockHistorical
            ->expects(self::once())
            ->method('getHistoricalData')
            ->willReturn(self::RESPONSE);

        $priceListService = new PriceListService($mockSerializer, $mockHistorical);

        $response = $priceListService->handleHistoricalData(self::CONTEXT);
        $this->assertEquals(self::RESPONSE, $response);
    }
}
