<?php

namespace App\Services\CreditCalculations\Contract;

interface InstallmentsInterface
{
    public function schedule(): static;
    public function scheduleShorterPeriod(): static;
    public function scheduleSmallerInstallment(): static;
    public function get(): array;
}
