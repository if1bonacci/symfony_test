<?php

namespace App\Message;

use App\DTO\PricesListRequestInterface;
use Symfony\Component\Mime\Email;

class SendEmailNotification implements MessageInterface
{
    const MESSAGE_BODY = 'From %s to %s';
    const DATE_FORMAT = 'Y-m-d';
    public function __construct(private readonly Email $email){}

    public function addSubject(string $subject): MessageInterface
    {
        $this->email->subject($subject);

        return $this;
    }

    public function addBody(PricesListRequestInterface $pricesListDto): MessageInterface
    {
        $body = sprintf(
            self::MESSAGE_BODY,
            $pricesListDto->getStartDate()->format(self::DATE_FORMAT),
            $pricesListDto->getEndDate()->format(self::DATE_FORMAT)
        );

        $this->email->html($body);

        return $this;
    }

    public function addRecipient(string $recipient): MessageInterface
    {
        $this->email->to($recipient);

        return $this;
    }

    public function getMessage(): mixed
    {
        return $this->email;
    }
}
