<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class IsValidDate
{
    public string $message = 'The date "{{ string }}" is inappropriate.';

    public string $mode;
}
