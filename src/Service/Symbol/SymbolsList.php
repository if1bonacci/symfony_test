<?php

namespace App\Service\Symbol;

interface SymbolsList
{
    public function findSymbol(string $symbol): bool;
    public function getListOfSymbols(): array;
}
