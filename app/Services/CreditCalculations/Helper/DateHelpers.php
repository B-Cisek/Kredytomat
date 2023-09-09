<?php

declare(strict_types=1);

namespace App\Services\CreditCalculations\Helper;

use Carbon\Carbon;

trait DateHelpers
{
    protected function getNextMonth(Carbon $currentDate): Carbon
    {
        return $currentDate->month === 12
            ? $currentDate->copy()->addYear()->startOfYear()
            : $currentDate->copy()->addMonth();
    }

    protected function getAvgCreditDays(Carbon $startDate, int $period): float
    {
        $endDate = $startDate->copy()->addMonths($period * 12);
        $days = $this->getNumberDaysFromTwoDates($startDate, $endDate);

        return $days / ($period * 12);
    }

    protected function getNumberDaysFromTwoDates(Carbon $startDate, Carbon $endDate): int
    {
        return $startDate->diffInDays($endDate);
    }

    protected function isDateInRange(Carbon $dateToCheck, Carbon $startDate, Carbon $endDate): bool
    {
        return $dateToCheck->greaterThanOrEqualTo($startDate) && $dateToCheck->lessThanOrEqualTo($endDate);
    }
}
