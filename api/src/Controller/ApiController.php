<?php

namespace App\Controller;

use App\Service\PriceList\PriceListInterface;
use App\Service\Symbol\SymbolInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\Cache;

#[Route('/api')]
class ApiController
{
    #[Route('/prices-list', methods: ['POST'])]
    public function pricesList(Request $request, PriceListInterface $priceListNotify): JsonResponse
    {
        return new JsonResponse(
            $priceListNotify->handleHistoricalData($request->getContent()),
            JsonResponse::HTTP_OK
        );
    }

    #[Route('/list-of-symbols', methods: ['GET'])]
    #[Cache(public: true, maxage: 3600, mustRevalidate: true)]
    public function listOfSymbols(SymbolInterface $symbolsList): JsonResponse
    {
        return new JsonResponse(
            $symbolsList->getListOfSymbols(),
            JsonResponse::HTTP_OK
        );
    }
}
