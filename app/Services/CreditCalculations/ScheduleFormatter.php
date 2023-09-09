<?php

declare(strict_types=1);

namespace App\Services\CreditCalculations;

class ScheduleFormatter
{
    private static array $columnsToFormat = [2, 3, 4, 5, 6, 10];

    public static function format(array $schedule): array
    {
        foreach ($schedule as &$row) {
            foreach ($row as $key => &$value) {
                if (in_array($key, self::$columnsToFormat)) {
                    $value = round($value, 2);
                }
            }
        }

        return $schedule;
    }
}
