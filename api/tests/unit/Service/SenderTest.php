<?php

declare(strict_types=1);

namespace App\Tests\unit\Service;

use App\Service\ExternalRequest\OptionInterface;
use App\Service\ExternalRequest\RequestBuilder;
use App\Service\ExternalRequest\RequestBuilderInterface;
use App\Service\ExternalRequest\Sender;
use App\Service\ExternalRequest\SenderInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SenderTest extends TestCase
{
    const TEST_URL = 'http://example.com/get/super/data';

    const TEST_DATA = '
    [
        {
            "Company Name": "iShares MSCI All Country Asia Information Technology Index Fund",
            "Financial Status": "N",
            "Market Category": "G",
            "Round Lot Size": 100,
            "Security Name": "iShares MSCI All Country Asia Information Technology Index Fund",
            "Symbol": "AAIT",
            "Test Issue": "N"
        },
        {
            "Company Name": "American Airlines Group, Inc.",
            "Financial Status": "N",
            "Market Category": "Q",
            "Round Lot Size": 100,
            "Security Name": "American Airlines Group, Inc. - Common Stock",
            "Symbol": "AAL",
            "Test Issue": "N"
        }
    ]
    ';

    private SenderInterface $sender;

    private OptionInterface $mockOption;

    private RequestBuilderInterface $mockRequestBuilder;

    protected function setUp(): void
    {
        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockClientResponse = $this->createMock(ResponseInterface::class);
        $mockClientResponse
            ->expects(self::once())
            ->method('getContent')
            ->willReturn(self::TEST_DATA);
        $mockHttpClient
            ->expects(self::once())
            ->method('request')
            ->with(Request::METHOD_GET, self::TEST_URL, [])
            ->willReturn($mockClientResponse);
        $this->mockOption = $this->createMock(OptionInterface::class);
        $this->mockOption
            ->expects(self::once())
            ->method('getData')
            ->willReturn([]);

        $this->mockRequestBuilder = $this->createMock(RequestBuilderInterface::class);
        $this->mockRequestBuilder
            ->expects(self::once())
            ->method('getMethod')
            ->willReturn(Request::METHOD_GET);
        $this->mockRequestBuilder
            ->expects(self::once())
            ->method('getUrl')
            ->willReturn(self::TEST_URL);
        $this->mockRequestBuilder
            ->expects(self::exactly(2))
            ->method('getOptions')
            ->willReturn($this->mockOption);

        $this->sender = new Sender();
        $this->sender->setClient($mockHttpClient);
    }

    public function testSendSuccess()
    {
        $result = $this->sender->send($this->mockRequestBuilder);

        $this->assertEquals(self::TEST_DATA, $result);
    }
}
