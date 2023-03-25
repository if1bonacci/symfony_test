<?php

namespace App\Service\HistoricalData;

use App\DTO\Price;
use App\DTO\PricesListRequestInterface;
use App\Exception\ApiException;
use App\Exception\ValidationExceptionData;
use App\Service\ExternalRequest\OptionInterface;
use App\Service\ExternalRequest\RequestBuilder;
use App\Service\ExternalRequest\RequestBuilderInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HistoricalDataService implements HistoricalDataInterface
{
    const REQUEST_FIELD = 'symbol';

    public function __construct(
        private readonly HttpClientInterface     $historicalClient,
        private readonly ContainerBagInterface   $params,
        private readonly SerializerInterface     $dtoSerializer,
        private readonly RequestBuilderInterface $requestBuilder,
        private readonly OptionInterface         $option,
        private readonly ValidatorInterface      $validator
    )
    {
    }

    public function getHistoricalData(PricesListRequestInterface $dto): array
    {
        $response = $this->requestBuilder
            ->setClient($this->historicalClient)
            ->setMethod(RequestBuilder::REQUEST_GET)
            ->setUrl($this->params->get('app.historical_data_link'))
            ->setOptions($this->option->setQueryParams([
                self::REQUEST_FIELD => $dto->getSymbol()
            ]))
            ->send();

        $priceDto = $this->dtoSerializer->deserialize($response, Price::class, JsonEncoder::FORMAT);
        $violations = $this->validator->validate($priceDto);
        if (count($violations) > 0) {
            throw new ApiException(new ValidationExceptionData(
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                ValidationExceptionData::ERROR_NAME,
                $violations
            ));
        }
        return $priceDto->getPrices();
    }
}
