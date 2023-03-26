<?php

namespace App\Controller;

use App\Service\PriceList\PriceListInterface;
use App\Service\Symbol\SymbolInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiController
{
    #[Route('/prices-list', methods: ['POST'])]
    public function pricesList(Request $request, PriceListInterface $priceListService): JsonResponse
    {
        return new JsonResponse(
            $priceListService->handleHistoricalData($request->getContent()),
            JsonResponse::HTTP_OK
        );
    }

    #[Route('/list-of-symbols', methods: ['GET'])]
    public function listOfSymbols(SymbolInterface $symbolsList): JsonResponse
    {
        return new JsonResponse(
            $symbolsList->getListOfSymbols(),
            JsonResponse::HTTP_OK
        );
    }
}
