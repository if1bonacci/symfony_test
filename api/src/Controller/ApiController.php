<?php

namespace App\Controller;

use App\DTO\PricesListRequest;
use App\Service\PriceList\PriceListInterface;
use App\Service\Symbol\SymbolInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class ApiController
{
    const CACHING_TIME = 3600;

    #[Route('/prices-list', methods: ['POST'])]
    public function pricesList(
        Request             $request,
        PriceListInterface  $priceListNotify,
        SerializerInterface $dtoSerializer,
    ): JsonResponse
    {
        $pricesListDto = $dtoSerializer->deserialize($request->getContent(), PricesListRequest::class, JsonEncoder::FORMAT);

        return new JsonResponse(
            $priceListNotify->handleHistoricalData($pricesListDto),
            JsonResponse::HTTP_OK
        );
    }

    #[Route('/list-of-symbols', methods: ['GET'])]
    #[Cache(maxage: self::CACHING_TIME, public: true, mustRevalidate: true)]
    public function listOfSymbols(SymbolInterface $symbolsList): JsonResponse
    {
        return new JsonResponse(
            $symbolsList->getListOfSymbols(),
            JsonResponse::HTTP_OK
        );
    }
}
