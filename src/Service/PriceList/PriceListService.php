<?php

namespace App\Service\PriceList;

use App\DTO\PricesListRequest;
use App\Message\MessageInterface;
use App\Service\HistoricalData\HistoricalDataInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class PriceListService implements PriceListInterface
{
    const REQUIRED_FIELD = 'date';

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

        return $this->checkDateRange(
            $this->historicalData->getHistoricalData($pricesListDto),
            $pricesListDto
        );
    }

    /***
     * @todo need to rewrite!!!!
     */
    private function checkDateRange(array $prices, $pricesListDto): array
    {
        $result = [];
        $startDate = strtotime($pricesListDto->getStartDate()->format(DATE_ATOM));
        $endDate = strtotime($pricesListDto->getEndDate()->format(DATE_ATOM));

        foreach ($prices as $price) {
            if ($price[self::REQUIRED_FIELD] >= $startDate && $price[self::REQUIRED_FIELD] <= $endDate) {
                $result[] = $price;
            }
        }

        return $result;
    }
}
