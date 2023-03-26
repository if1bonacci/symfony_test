<?php

namespace App\DTO;

use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as AcmeAssert;

class PricesListRequest implements DTOInterface, PricesListRequestInterface
{
    #[Assert\NotBlank]
    #[AcmeAssert\IsValidSymbol]
    protected string $symbol;
    #[Assert\NotBlank]
    #[Assert\LessThanOrEqual(propertyPath: 'endDate')]
    #[Assert\LessThanOrEqual(value: 'today')]
    protected DateTime $startDate;
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(propertyPath: 'startDate')]
    #[Assert\LessThanOrEqual(value: 'today')]
    protected DateTime $endDate;
    #[Assert\NotBlank]
    protected string $email;

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = strtoupper($symbol);

        return $this;
    }

    public function getStartDate(): DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(DateTime $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(DateTime $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
