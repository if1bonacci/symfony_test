<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class Price implements DTOInterface
{
    #[Assert\NotBlank(message: "Unfortunately, we don't have information about the company.")]
    private array $prices;

    private int $firstTradeDate;

    public function getPrices(): array
    {
        return $this->prices;
    }

    public function setPrices(array $prices): self
    {
        $this->prices = $prices;

        return $this;
    }

    public function getFirstTradeDate(): int
    {
        return $this->firstTradeDate;
    }

    public function setFirstTradeDate(int $firstTradeDate): self
    {
        $this->firstTradeDate = $firstTradeDate;

        return $this;
    }
}
