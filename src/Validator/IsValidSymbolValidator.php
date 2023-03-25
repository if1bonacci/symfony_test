<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class IsValidSymbolValidator extends ConstraintValidator
{
    public function __construct(){}
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

        $externalSymbolService = false;
        if ($externalSymbolService) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
