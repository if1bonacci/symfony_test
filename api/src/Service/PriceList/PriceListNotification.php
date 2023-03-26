<?php

namespace App\Service\PriceList;

use App\DTO\PricesListRequestInterface;
use App\Message\MessageInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class PriceListNotification implements PriceListInterface
{
    public function __construct(
        private readonly PriceListInterface  $priceList,
        private readonly MessageBusInterface $messageBus,
        private readonly MessageInterface    $emailMessage,
    ) {}

    public function handleHistoricalData(PricesListRequestInterface $pricesListDto): array
    {
        $prices = $this->priceList->handleHistoricalData($pricesListDto);
        $message = $this->emailMessage
            ->addRecipient($pricesListDto->getEmail())
            ->addSubject($pricesListDto->getSymbol())
            ->addBody($pricesListDto);

        $this->messageBus->dispatch($message);

        return $prices;
    }
}
