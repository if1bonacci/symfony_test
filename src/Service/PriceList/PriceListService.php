<?php

namespace App\Service\PriceList;

use App\DTO\PricesListRequest;
use App\Exception\ApiException;
use App\Exception\ValidationExceptionData;
use App\Service\HistoricalData\HistoricalDataInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PriceListService implements PriceListInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly HistoricalDataInterface $historicalData,
    ){}
    public function handleHistoricalData(string $content): array
    {
        $pricesListDto = $this->serializer->deserialize($content, PricesListRequest::class, JsonEncoder::FORMAT);
        $violations = $this->validator->validate($pricesListDto);

        if (count($violations) > 0) {
            throw new ApiException(new ValidationExceptionData(
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                ValidationExceptionData::ERROR_NAME,
                $violations
            ));
        }

        return $this->historicalData->getHistoricalData($pricesListDto);
    }

}
