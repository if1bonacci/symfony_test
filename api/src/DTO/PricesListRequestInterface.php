<?php

namespace App\DTO;

use DateTime;

interface PricesListRequestInterface
{
    public function getSymbol(): string;

    public function setSymbol(string $symbol): self;

    public function getStartDate(): DateTime;

    public function setStartDate(DateTime $startDate): self;

    public function getEndDate(): DateTime;

    public function setEndDate(DateTime $endDate): self;

    public function getEmail(): string;

    public function setEmail(string $email): self;

    public function getStartDateInt(): int;

    public function getEndDateInt(): int;
}
