<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use App\DTO\PricesListRequest;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/prices-list', methods: ['POST'])]
    public function pricesList(
        Request $request,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $pricesListDto = $serializer->deserialize($request->getContent(), PricesListRequest::class, JsonEncoder::FORMAT);

        return $this->json(['name' => 'John Doe'], JsonResponse::HTTP_OK);
    }
}
