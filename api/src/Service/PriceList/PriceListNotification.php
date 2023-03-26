<?php

namespace App\Service\PriceList;

use App\Message\MessageInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class PriceListNotification extends PriceList
{
    public function __construct(
        private readonly PriceListInterface  $priceListByPeriod,
        private readonly MessageBusInterface $messageBus,
        private readonly MessageInterface    $emailMessage,
    )
    {
    }

    public function handleHistoricalData(string $content): array
    {
        $prices = $this->priceListByPeriod->handleHistoricalData($content);
        $this->pricesListDto = $this->priceListByPeriod->getDto();

        $this->messageBus->dispatch(
            $this->emailMessage
                ->addRecipient($this->pricesListDto->getEmail())
                ->addSubject($this->pricesListDto->getSymbol())
                ->addBody($this->pricesListDto)
        );

        return $prices;
    }
}
