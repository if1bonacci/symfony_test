<?php

namespace App\Message;

use App\DTO\PricesListRequestInterface;

interface MessageInterface
{
    public function addSubject(string $subject): self;

    public function addBody(PricesListRequestInterface $pricesListDto): self;

    public function addRecipient(string $recipient): self;

    public function getMessage(): mixed;

    public function getSubject(): ?string;
}
