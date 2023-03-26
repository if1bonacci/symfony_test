<?php

namespace App\Tests\unit\Service;

use App\DTO\PricesListRequest;
use App\DTO\PricesListRequestInterface;
use App\Message\MessageInterface;
use App\Service\HistoricalData\HistoricalDataInterface;
use App\Service\PriceList\PriceListByPeriod;
use App\Service\PriceList\PriceListService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
class PriceListByPeriodServiceTest extends TestCase
{
    const LIST_OF_PRICES = '{"prices":[{"date":1179405400,"open":101.9800033569336,"high":103.4800033569336,"low":101.86000061035156,"close":103.03500366210938,"volume":3665412,"adjclose":103.03500366210938},{"date":1679319000,"open":101.05999755859375,"high":102.58000183105469,"low":100.79000091552734,"close":101.93000030517578,"volume":26015800,"adjclose":101.93000030517578}, {"date":1679319000,"open":101.05999755859375,"high":102.58000183105469,"low":100.79000091552734,"close":101.93000030517578,"volume":26015800,"adjclose":101.93000030517578}]}';

    const START_DATE = '2023-03-23';
    const END_DATE = '2023-03-24';

    const CONTEXT = '
        {
            "symbol": "GOOG",
            "startDate": "2023-03-23",
            "endDate": "2023-03-24",
            "email": "test@example.com"
        }
    ';

    const RESPONSE = [
        [
            "date" => 1679534600, //2023-03-23
            "open" => 101.9800033569336,
            "high" => 105.95999908447266,
            "low" => 101.86000061035156,
            "close" => 105.83999633789062,
            "volume" => 33077400,
        ],
        [
            "date" => 1388516401, //2013-12-31
            "open" => 101.05999755859375,
            "high" => 102.58000183105469,
            "low" => 100.79000091552734,
            "close" => 101.93000030517578,
            "volume" => 26033900,
        ],
        [
            "date" => 1679664600,  //2023-03-24
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
            ->method('getStartDate')
            ->willReturn(new \DateTime(self::START_DATE));
        $mockPriceReq
            ->expects(self::once())
            ->method('getEndDate')
            ->willReturn(new \DateTime(self::END_DATE));

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

        $priceListByPeriod = new PriceListByPeriod($priceListService);
        $response = $priceListByPeriod->handleHistoricalData(self::CONTEXT);

        $this->assertEquals(2, count($response));
    }
}
