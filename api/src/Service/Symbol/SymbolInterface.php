<?php

namespace App\Service\Symbol;

interface SymbolInterface
{
    public function findSymbol(string $symbol): bool;

    public function getListOfSymbols(): array;

    public function findCompanyBySymbol(string $symbol): string;
}
