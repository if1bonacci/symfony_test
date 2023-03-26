<?php

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationList;

class ValidationExceptionData extends ApiExceptionData
{
    const ERROR_NAME = 'ConstraintViolationList';

    private ConstraintViolationList $violations;

    public function __construct(int $statusCode, string $type, ConstraintViolationList $violations)
    {
        parent::__construct($statusCode, $type);

        $this->violations = $violations;
    }

    public function toArray(): array
    {
        return [
            'error' => self::ERROR_NAME,
            'violations' => $this->getViolationsArray()
        ];
    }

    private function getViolationsArray(): array
    {
        $violations = [];

        foreach ($this->violations as $violation) {

            $violations[] = [
                'propertyPath' => $violation->getPropertyPath(),
                'message' => $violation->getMessage()
            ];
        }

        return $violations;
    }
}
