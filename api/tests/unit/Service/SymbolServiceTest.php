<?php

declare(strict_types=1);

namespace App\Tests\unit\Service;

use App\Service\ExternalRequest\RequestBuilderInterface;
use App\Service\Symbol\SymbolService;
use App\Service\Symbol\SymbolInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class SymbolServiceTest extends TestCase
{
    const LIST_OF_SYMBOLS = [
        [
            'Company Name' => 'test1',
            'Symbol' => 'TEST1'
        ],
        [
            'Company Name' => 'test2',
            'Symbol' => 'TEST2'
        ],
        [
            'Company Name' => 'test3',
            'Symbol' => 'TEST3'
        ]
    ];

    const LINK_TO_RESOURCE = 'https://example.com';

    private SymbolInterface $externalSymbolService;

    protected function setUp(): void
    {
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
            ->method('send')
            ->willReturn(\json_encode(self::LIST_OF_SYMBOLS));

        $this->externalSymbolService = new SymbolService($mockRequestBuilder, self::LINK_TO_RESOURCE);
    }

    public function testGetListOfSymbols()
    {
        $response = $this->externalSymbolService->getListOfSymbols();
        $this->assertEquals(self::LIST_OF_SYMBOLS, $response);
    }

    /**
     * @dataProvider symbolsProvider
     */
    public function testFindSymbol($expectedStatus, $symbol)
    {
        $response = $this->externalSymbolService->findSymbol($symbol);
        $this->assertEquals($expectedStatus, $response);
    }

    /**
     * @dataProvider companyNameProvider
     */
    public function testFindCompanyName($expectedStatus, $symbol)
    {
        $response = $this->externalSymbolService->findCompanyBySymbol($symbol);
        $this->assertEquals($expectedStatus, $response);
    }

    public function symbolsProvider(): \Generator
    {
        yield 'exist' => [
            false,
            'TEST1',
        ];
        yield 'exist_2' => [
            false,
            'TEST3',
        ];
        yield 'exist_3' => [
            false,
            'TEST2',
        ];
        yield 'not_exist' => [
            true,
            'TEST4',
        ];
    }

    public function companyNameProvider(): \Generator
    {
        yield 'exist' => [
            'test1',
            'TEST1',
        ];
        yield 'exist_2' => [
            'test3',
            'TEST3',
        ];
        yield 'exist_3' => [
            'test2',
            'TEST2',
        ];
        yield 'not_exist' => [
            'TEST4',
            'TEST4',
        ];
    }
}
