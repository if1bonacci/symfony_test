<?php

namespace App\Service\Notification;

use App\Message\MessageInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class EmailNotification implements NotificationInterface
{
    public function __construct(private MailerInterface $mailer)
    {}

    public function send(MessageInterface $message): void
    {
        try {
            $this->mailer->send($message->getMessage());
        } catch (TransportExceptionInterface $e) {
            var_dump($e);
        }
    }
}
