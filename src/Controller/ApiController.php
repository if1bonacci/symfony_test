<?php

namespace App\Controller;

use App\Exception\ApiException;
use App\Exception\ValidationExceptionData;
use App\Service\Symbol\SymbolsList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use App\DTO\PricesListRequest;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/prices-list', methods: ['POST'])]
    public function pricesList(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ): JsonResponse
    {
        $pricesListDto = $serializer->deserialize($request->getContent(), PricesListRequest::class, JsonEncoder::FORMAT);
        $violations = $validator->validate($pricesListDto);

        if (count($violations) > 0) {
            throw new ApiException(new ValidationExceptionData(
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                ValidationExceptionData::ERROR_NAME,
                $violations
            ));
        }

        return $this->json(['name' => 'John Doe'], JsonResponse::HTTP_OK);
    }

    #[Route('/list-of-symbols', methods: ['GET'])]
    public function listOfSymbols(SymbolsList $symbolsList): JsonResponse
    {
        return $this->json($symbolsList->getListOfSymbols(), JsonResponse::HTTP_OK);
    }
}
