<?php

namespace App\Tests\unit\Service;

use App\Service\ExternalRequest\RequestBuilderInterface;
use App\Service\Symbol\ExternalSymbolService;
use App\Service\Symbol\SymbolsList;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class ExternalSymbolServiceTest extends TestCase
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
    private SymbolsList $externalSymbolService;

    protected function setUp(): void
    {
        $containerBag = $this->createMock(ContainerBagInterface::class);
        $containerBag
            ->expects(self::once())
            ->method('get')
            ->with('app.symbols_list_link')
            ->willReturn(self::LINK_TO_RESOURCE);

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
            ->willReturn(json_encode(self::LIST_OF_SYMBOLS, true));

        $this->externalSymbolService = new ExternalSymbolService($mockRequestBuilder, $containerBag);
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
}
