<?php

namespace App\DTO;

class PriceItem implements DTOInterface
{
    protected int $date;
    protected float $open;
    protected float $high;
    protected float $low;
    protected float $close;
    protected int $volume;
    protected float $adjclose;

    /**
     * @return int
     */
    public function getDate(): int
    {
        return $this->date;
    }

    /**
     * @param int $date
     */
    public function setDate(int $date): void
    {
        $this->date = $date;
    }

    /**
     * @return float
     */
    public function getOpen(): float
    {
        return $this->open;
    }

    /**
     * @param float $open
     */
    public function setOpen(float $open): void
    {
        $this->open = $open;
    }

    /**
     * @return float
     */
    public function getHigh(): float
    {
        return $this->high;
    }

    /**
     * @param float $high
     */
    public function setHigh(float $high): void
    {
        $this->high = $high;
    }

    /**
     * @return float
     */
    public function getLow(): float
    {
        return $this->low;
    }

    /**
     * @param float $low
     */
    public function setLow(float $low): void
    {
        $this->low = $low;
    }

    /**
     * @return float
     */
    public function getClose(): float
    {
        return $this->close;
    }

    /**
     * @param float $close
     */
    public function setClose(float $close): void
    {
        $this->close = $close;
    }

    /**
     * @return int
     */
    public function getVolume(): int
    {
        return $this->volume;
    }

    /**
     * @param int $volume
     */
    public function setVolume(int $volume): void
    {
        $this->volume = $volume;
    }

    /**
     * @return float
     */
    public function getAdjclose(): float
    {
        return $this->adjclose;
    }

    /**
     * @param float $adjclose
     */
    public function setAdjclose(float $adjclose): void
    {
        $this->adjclose = $adjclose;
    }
}
