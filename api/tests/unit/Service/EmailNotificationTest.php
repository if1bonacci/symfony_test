<?php

declare(strict_types=1);

namespace App\Tests\unit\Service;

use App\Message\MessageInterface;
use App\Service\Notification\EmailNotification;
use App\Service\Symbol\SymbolInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\RawMessage;

class EmailNotificationTest extends TestCase
{
    const COMPANY_NAME = 'Apple inc.';
    public function testSuccess()
    {
        $mockRawMessage = $this->createMock(RawMessage::class);

        $mockMailer = $this->createMock(MailerInterface::class);
        $mockMailer
            ->expects(self::once())
            ->method('send');

        $mockMessage = $this->createMock(MessageInterface::class);
        $mockSymbol = $this->createMock(SymbolInterface::class);
        $mockSymbol
            ->expects(self::once())
            ->method('findCompanyBySymbol')
            ->with(self::COMPANY_NAME)
            ->willReturn(self::COMPANY_NAME);
        $mockMessage
            ->expects(self::once())
            ->method('getSubject')
            ->willReturn(self::COMPANY_NAME);
        $mockMessage
            ->expects(self::once())
            ->method('getMessage')
            ->willReturn($mockRawMessage);
        $mockMessage
            ->expects(self::once())
            ->method('addSubject')
            ->with(self::COMPANY_NAME)
            ->willReturn($mockMessage);

        $emailNotification = new EmailNotification($mockMailer, $mockSymbol);
        $emailNotification->send($mockMessage);
    }
}
