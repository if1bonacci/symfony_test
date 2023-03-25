<?php

declare(strict_types=1);

namespace App\Tests\unit\Service;

use App\Message\MessageInterface;
use App\Service\Notification\EmailNotification;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\RawMessage;

class EmailNotificationTest extends TestCase
{
    public function testSuccess()
    {
        $mockRawMessage = $this->createMock(RawMessage::class);

        $mockMailer = $this->createMock(MailerInterface::class);
        $mockMailer
            ->expects(self::once())
            ->method('send');

        $mockMessage = $this->createMock(MessageInterface::class);
        $mockMessage
            ->expects(self::once())
            ->method('getMessage')
            ->willReturn($mockRawMessage);

        $emailNotification = new EmailNotification($mockMailer);
        $emailNotification->send($mockMessage);
    }
}
