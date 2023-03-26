<?php

namespace App\Controller;

use App\Service\Symbol\SymbolInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
final class CompanyController extends ApiController
{
    private const CACHING_TIME = 3600;

    #[Route('/companies', methods: ['GET'])]
//    #[Cache(maxage: self::CACHING_TIME, public: true, mustRevalidate: true)]
    public function companies(SymbolInterface $symbolsList): JsonResponse
    {
        $companies = $symbolsList->getListOfSymbols();

        return $this->httpOk($companies);
    }
}
