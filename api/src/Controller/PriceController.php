<?php

namespace App\Controller;

use App\DTO\PricesListRequest;
use App\Service\PriceList\PriceListInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
final class PriceController extends ApiController
{
    #[Route('/prices-list', methods: ['POST'])]
    public function pricesList(
        Request             $request,
        PriceListInterface  $priceListNotify,
        SerializerInterface $dtoSerializer,
    ): JsonResponse
    {
        $pricesListDto = $dtoSerializer->deserialize($request->getContent(), PricesListRequest::class, JsonEncoder::FORMAT);
        $prices = $priceListNotify->handleHistoricalData($pricesListDto);

        return $this->httpOk($prices);
    }
}
