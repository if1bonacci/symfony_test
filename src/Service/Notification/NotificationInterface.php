<?php

namespace App\Service\Notification;

use App\Message\MessageInterface;

interface NotificationInterface
{
    public function send(MessageInterface $message): void;
}
