<?php

declare(strict_types=1);

namespace App\Tests\unit\Service;

use App\DTO\PricesListRequestInterface;
use App\Message\SendEmailNotification;
use App\Message\MessageInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mime\Email;

class EmailMessageTest extends TestCase
{
    private const EMAIL = 'test_case@test.com';
    private const SUBJECT = 'test_subject';

    private MockObject $email;
    private MessageInterface $message;

    protected function setUp(): void
    {
        $this->email = $this->createMock(Email::class);
        $this->message = new SendEmailNotification($this->email);
    }

    public function testAddSubject(): void
    {
        $result = $this->message->addSubject(self::SUBJECT);
        $this->assertInstanceOf(SendEmailNotification::class, $result);
    }

    public function testAddBody(): void
    {
        $mockHistoryReq = $this->createMock(PricesListRequestInterface::class);
        $mockHistoryReq
            ->expects(self::once())
            ->method('getStartDate')
            ->willReturn(new \DateTime());
        $mockHistoryReq
            ->expects(self::once())
            ->method('getEndDate')
            ->willReturn(new \DateTime());

        $result = $this->message->addBody($mockHistoryReq);
        $this->assertInstanceOf(SendEmailNotification::class, $result);
    }

    public function testAddRecipient(): void
    {
        $result = $this->message->addRecipient(self::EMAIL);
        $this->assertInstanceOf(SendEmailNotification::class, $result);
    }

    public function testGetMessage(): void
    {
        $result = $this->message->getMessage();
        $this->assertInstanceOf(Email::class, $result);
    }
}
