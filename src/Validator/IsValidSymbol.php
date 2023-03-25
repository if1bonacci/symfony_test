<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class IsValidSymbol extends Constraint
{
    public string $message = 'The symbol "{{ string }}" is inappropriate.';

    public string $mode;
}
