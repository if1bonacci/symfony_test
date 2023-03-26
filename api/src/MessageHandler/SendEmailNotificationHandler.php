<?php

namespace App\MessageHandler;

use App\Service\Notification\NotificationInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Message\MessageInterface;

#[AsMessageHandler]
class SendEmailNotificationHandler
{
    public function __construct(
        private readonly NotificationInterface $notification
    ) {}

    public function __invoke(MessageInterface $message): void
    {
        $this->notification->send($message);
    }
}
