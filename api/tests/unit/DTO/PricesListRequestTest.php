<?php

declare(strict_types=1);

namespace App\Tests\unit\DTO;

use PHPUnit\Framework\TestCase;
use DateTime;
use TypeError;
use App\DTO\PricesListRequest;
class PricesListRequestTest extends TestCase
{
    const EMAIL = 'test@example.com';

    const SYMBOL = 'TEST1';

    private PricesListRequest $pricesListRequest;

    protected function setUp(): void
    {
        $this->pricesListRequest = new PricesListRequest();
    }

    public function testSetValidValues()
    {
        $now = new DateTime();
        $this->pricesListRequest->setSymbol(self::SYMBOL);
        $this->pricesListRequest->setEmail(self::EMAIL);
        $this->pricesListRequest->setStartDate($now);
        $this->pricesListRequest->setEndDate($now);

        $this->assertEquals(self::SYMBOL, $this->pricesListRequest->getSymbol());
        $this->assertIsString($this->pricesListRequest->getSymbol());

        $this->assertEquals(self::EMAIL, $this->pricesListRequest->getEmail());
        $this->assertIsString($this->pricesListRequest->getEmail());

        $this->assertEquals($now, $this->pricesListRequest->getStartDate());
        $this->assertEquals($now, $this->pricesListRequest->getEndDate());
    }

    public function testTypeErrorSymbol()
    {
        $this->expectException(TypeError::class);

        $this->pricesListRequest->setSymbol(new DateTime());
    }

    public function testTypeErrorEmail()
    {
        $this->expectException(TypeError::class);

        $this->pricesListRequest->setEmail(new DateTime());
    }

    public function testTypeErrorStartDate()
    {
        $this->expectException(TypeError::class);

        $this->pricesListRequest->setStartDate(true);
    }

    public function testTypeErrorEndDate()
    {
        $this->expectException(TypeError::class);

        $this->pricesListRequest->setEndDate(true);
    }
}
