<?php

namespace App\Event;

use App\DTO\DTOInterface;
use Symfony\Contracts\EventDispatcher\Event;

class AfterDtoCreatedEvent extends Event
{
    public const NAME = 'dto.created';

    public function __construct(
        protected DTOInterface $dto
    ) {}

    public function getDto(): DTOInterface
    {
        return $this->dto;
    }
}
