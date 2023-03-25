<?php

namespace App\Tests\unit\Validator;

use App\Service\Symbol\SymbolsList;
use App\Validator\IsValidSymbol;
use App\Validator\IsValidSymbolValidator;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class IsValidSymbolValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): ConstraintValidatorInterface
    {
        $mockSymbolService = $this->createMock(SymbolsList::class);
        $mockSymbolService
            ->method('findSymbol')
            ->willReturn(true);

        return new IsValidSymbolValidator($mockSymbolService);
    }

    public function testNullIsValid()
    {
        $this->validator->validate(null, new IsValidSymbol());

        $this->assertNoViolation();
    }

    /**
     * @dataProvider provideInvalidConstraints
     */
    public function testTrueIsInvalid($value, IsValidSymbol $constraint)
    {
        $this->validator->validate($value, $constraint);

        $this->buildViolation('myMessage')
            ->setParameter('{{ string }}', $value)
            ->assertRaised();
    }

    public function provideInvalidConstraints(): iterable
    {
        yield [ 'foobar', new IsValidSymbol(['message' => 'myMessage'])];
        yield [ '0', new IsValidSymbol(['message' => 'myMessage'])];
        yield [ '555', new IsValidSymbol(['message' => 'myMessage'])];
        yield [ 'ww23', new IsValidSymbol(['message' => 'myMessage'])];
    }
}
