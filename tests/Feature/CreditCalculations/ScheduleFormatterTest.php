<?php

namespace CreditCalculations;

use App\Services\CreditCalculations\Credit;
use App\Services\CreditCalculations\DecreasingInstallments;
use App\Services\CreditCalculations\Enum\CommissionType;
use App\Services\CreditCalculations\Enum\PeriodType;
use App\Services\CreditCalculations\ScheduleFormatter;
use Carbon\Carbon;
use Tests\TestCase;

class ScheduleFormatterTest extends TestCase
{
    public function test_schedule_is_correct_formatted(): void
    {
        $credit = new Credit(
            300_000,
            20,
            PeriodType::YEAR,
            6.44,
            0,
            0,
            CommissionType::NUMBER
        );

        $decreasingInstallments = new DecreasingInstallments(
            Carbon::create(2023),
            $credit
        );

        $schedule = ScheduleFormatter::format($decreasingInstallments->schedule()->get());

        $this->assertEquals(300000, $schedule[0][2]);
        $this->assertEquals(1640.88, $schedule[0][3]);
        $this->assertEquals(1250, $schedule[0][4]);
        $this->assertEquals(2890.88, $schedule[0][5]);
        $this->assertEquals(298750, $schedule[0][6]);

        $this->assertEquals(1250, $schedule[array_key_last($schedule)][2]);
        $this->assertEquals(6.84, $schedule[array_key_last($schedule)][3]);
        $this->assertEquals(1250, $schedule[array_key_last($schedule)][4]);
        $this->assertEquals(1256.84, $schedule[array_key_last($schedule)][5]);
        $this->assertEquals(0, $schedule[array_key_last($schedule)][6]);
    }
}
