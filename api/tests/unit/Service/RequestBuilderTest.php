<?php

namespace App\Tests\unit\Service;

use App\Service\ExternalRequest\OptionInterface;
use App\Service\ExternalRequest\RequestBuilder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class RequestBuilderTest extends TestCase
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

    private RequestBuilder $requestBuilder;
    protected function setUp(): void
    {
        $mockValidator = $this->createMock(ValidatorInterface::class);
        $mockValidatorConstrain = $this->createMock(ConstraintViolationListInterface::class);

        $mockValidator
            ->expects(self::once())
            ->method('validate')
            ->willReturn($mockValidatorConstrain);

        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockClientResponse = $this->createMock(ResponseInterface::class);
        $mockClientResponse
            ->expects(self::once())
            ->method('getContent')
            ->willReturn(self::TEST_DATA);
        $mockHttpClient
            ->expects(self::once())
            ->method('request')
            ->with(RequestBuilder::REQUEST_GET, self::TEST_URL, [])
            ->willReturn($mockClientResponse);

        $this->requestBuilder = new RequestBuilder($mockHttpClient, $mockValidator);
    }

    public function testSendSuccess()
    {
        $mockOption = $this->createMock(OptionInterface::class);
        $result = $this->requestBuilder
            ->setUrl(self::TEST_URL)
            ->setOptions($mockOption)
            ->setMethod(RequestBuilder::REQUEST_GET)
            ->send();

        $this->assertEquals(self::TEST_DATA, $result);
    }

    public function testSendSuccessWithEmptyOption()
    {
        $result = $this->requestBuilder
            ->setUrl(self::TEST_URL)
            ->setMethod(RequestBuilder::REQUEST_GET)
            ->send();

        $this->assertEquals(self::TEST_DATA, $result);
    }
}
