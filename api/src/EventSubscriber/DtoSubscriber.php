<?php

namespace App\EventSubscriber;

use App\Event\AfterDtoCreatedEvent;
use App\Exception\ApiException;
use App\Exception\ValidationExceptionData;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DtoSubscriber implements EventSubscriberInterface
{
    public const NAME = 'validateDto';
    public function __construct(private ValidatorInterface $validator)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AfterDtoCreatedEvent::NAME => self::NAME
        ];
    }

    public function validateDto(AfterDtoCreatedEvent $event): void
    {
        $dto = $event->getDto();

        $violations = $this->validator->validate($dto);
        if (count($violations) > 0) {
            throw new ApiException(
                new ValidationExceptionData(
                    JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
                    ValidationExceptionData::ERROR_NAME,
                    $violations
                )
            );
        }
    }
}
