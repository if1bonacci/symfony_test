<?php

namespace App\Validator;

use App\Service\Symbol\SymbolsList;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class IsValidSymbolValidator extends ConstraintValidator
{
    public function __construct(private SymbolsList $symbolService){}
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsValidSymbol) {
            throw new UnexpectedTypeException($constraint, IsValidSymbol::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if ($this->symbolService->findSymbol($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
