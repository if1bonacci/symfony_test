<?php

namespace App\Service\Notification;

use App\Message\MessageInterface;
use App\Service\Symbol\SymbolInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class EmailNotification implements NotificationInterface
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly SymbolInterface $symbolService,
        private readonly LoggerInterface $logger
    ) {}

    public function send(MessageInterface $message): void
    {
        try {
            $message = $message->addSubject(
                $this->symbolService->findCompanyBySymbol($message->getSubject())
            );

            $this->mailer->send($message->getMessage());
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
