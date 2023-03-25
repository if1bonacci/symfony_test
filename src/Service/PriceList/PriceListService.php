<?php

namespace App\Service\PriceList;

use App\DTO\PricesListRequest;
use App\Message\MessageInterface;
use App\Service\HistoricalData\HistoricalDataInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use App\Message\SendEmailNotification;

class PriceListService implements PriceListInterface
{
    public function __construct(
        private readonly SerializerInterface     $dtoSerializer,
        private readonly HistoricalDataInterface $historicalData,
        private readonly MessageBusInterface     $messageBus,
        private readonly MessageInterface        $emailMessage,
    )
    {
    }

    public function handleHistoricalData(string $content): array
    {
        $pricesListDto = $this->dtoSerializer->deserialize($content, PricesListRequest::class, JsonEncoder::FORMAT);

        $this->messageBus->dispatch(
            $this->emailMessage
                ->addRecipient($pricesListDto->getEmail())
                ->addSubject($pricesListDto->getSymbol())
                ->addBody($pricesListDto)
        );

        return $this->historicalData->getHistoricalData($pricesListDto);
    }
}
