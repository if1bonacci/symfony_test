<?php

declare(strict_types=1);

namespace App\Tests\unit\Service;

use App\DTO\Price;
use App\DTO\PricesListRequestInterface;
use App\Service\ExternalRequest\OptionInterface;
use App\Service\ExternalRequest\RequestBuilderInterface;
use App\Service\ExternalRequest\SenderInterface;
use App\Service\HistoricalData\HistoricalDataService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HistoricalDataServiceTest extends TestCase
{
    const LIST_OF_PRICES = '{"prices":[{"date":1679407629,"open":101.9800033569336,"high":103.4800033569336,"low":101.86000061035156,"close":103.03500366210938,"volume":3665412,"adjclose":103.03500366210938},{"date":1679319000,"open":101.05999755859375,"high":102.58000183105469,"low":100.79000091552734,"close":101.93000030517578,"volume":26015800,"adjclose":101.93000030517578}]}';

    const SYMBOL_TEST = 'test';

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

    const LINK_TO_RESOURCE = 'https://example.com';

    public function testGetHistoricalData()
    {
        $mockHttpClient = $this->createMock(HttpClientInterface::class);

        $mockOptions = $this->createMock(OptionInterface::class);
        $mockOptions
            ->expects(self::once())
            ->method('setQueryParams')
            ->with(['symbol' => self::SYMBOL_TEST])
            ->willReturn($mockOptions);

        $mockRequestBuilder = $this->createMock(RequestBuilderInterface::class);
        $mockRequestBuilder
            ->expects(self::once())
            ->method('setUrl')
            ->willReturn($mockRequestBuilder);
        $mockRequestBuilder
            ->expects(self::once())
            ->method('setMethod')
            ->willReturn($mockRequestBuilder);
        $mockRequestBuilder
            ->expects(self::once())
            ->method('setOptions')
            ->with($mockOptions)
            ->willReturn($mockRequestBuilder);

        $mockSender = $this->createMock(SenderInterface::class);
        $mockSender
            ->expects(self::once())
            ->method('setClient')
            ->willReturn($mockSender);

        $mockSender
            ->expects(self::once())
            ->method('send')
            ->with($mockRequestBuilder)
            ->willReturn(self::LIST_OF_PRICES);

        $mockPrice = $this->createMock(Price::class);
        $mockPrice
            ->expects(self::once())
            ->method('getPrices')
            ->willReturn(self::RESPONSE);

        $mockSerializer = $this->createMock(SerializerInterface::class);
        $mockSerializer
            ->expects(self::once())
            ->method('deserialize')
            ->with(self::LIST_OF_PRICES, Price::class, 'json')
            ->willReturn($mockPrice);

        $historicalDataService = new HistoricalDataService(
            $mockHttpClient,
            $mockSerializer,
            $mockRequestBuilder,
            $mockOptions,
            $mockSender,
            self::LINK_TO_RESOURCE
        );

        $mockPriceReqDto = $this->createMock(PricesListRequestInterface::class);
        $mockPriceReqDto
            ->expects(self::once())
            ->method('getSymbol')
            ->willReturn(self::SYMBOL_TEST);

        $response = $historicalDataService->getHistoricalData($mockPriceReqDto);

        $this->assertEquals(self::RESPONSE, $response);
    }
}
