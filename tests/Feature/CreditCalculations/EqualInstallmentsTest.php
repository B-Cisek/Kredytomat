<?php

namespace CreditCalculations;

use App\Services\CreditCalculations\Credit;
use App\Services\CreditCalculations\Enum\CommissionType;
use App\Services\CreditCalculations\Enum\PeriodType;
use App\Services\CreditCalculations\EqualInstallments;
use App\Services\CreditCalculations\ScheduleFormatter;
use Carbon\Carbon;
use Tests\TestCase;

class EqualInstallmentsTest extends TestCase
{
    private Credit $credit;
    private array $overpayments;
    private array $interestsRateChanges;

    public function __construct()
    {
        parent::__construct();

        $this->credit = new Credit(
            amountOfCredit: 300_000,
            period: 20,
            periodType: PeriodType::YEAR,
            margin: 3.72,
            wibor: 5,
            commission: 0,
            commissionType: CommissionType::NUMBER
        );

        $this->interestsRateChanges = [
            [
                'date' => [
                    'month' => 10,
                    'year' => 2024
                ],
                'value' => 5.72
            ],
            [
                'date' => [
                    'month' => 4,
                    'year' => 2025
                ],
                'value' => 8
            ],
            [
                'date' => [
                    'month' => 2,
                    'year' => 2027
                ],
                'value' => 7.52
            ]
        ];

        $this->overpayments = [
            [
                'start' => [
                    'month' => 11,
                    'year' => 2023
                ],
                'end' => [
                    'month' => 11,
                    'year' => 2023
                ],
                'overpayment' => 5_000
            ],
            [
                'start' => [
                    'month' => 5,
                    'year' => 2026
                ],
                'end' => [
                    'month' => 5,
                    'year' => 2026
                ],
                'overpayment' => 6_600
            ]
        ];
    }

    public function test_schedule_calculation_with_rate_changes (): void
    {
        $equalInstallments = new EqualInstallments(
            date: Carbon::create(2023, 1),
            credit: $this->credit,
            interestsRateChanges: $this->interestsRateChanges
        );

        $schedule = ScheduleFormatter::format($equalInstallments->schedule()->get());

        $this->assertEquals(31, $schedule[0][1]);
        $this->assertEquals(300000, $schedule[0][2]);
        $this->assertEquals(2221.81, $schedule[0][3]);
        $this->assertEquals(445.58, $schedule[0][4]);
        $this->assertEquals(2667.39, $schedule[0][5]);
        $this->assertEquals(299554.42, $schedule[0][6]);

        $this->assertEquals(31, $schedule[array_key_last($schedule)][1]);
        $this->assertEquals(2280.31, $schedule[array_key_last($schedule)][2]);
        $this->assertEquals(48.15, $schedule[array_key_last($schedule)][3]);
        $this->assertEquals(2280.31, $schedule[array_key_last($schedule)][4]);
        $this->assertEquals(2328.46, $schedule[array_key_last($schedule)][5]);
        $this->assertEquals(0, $schedule[array_key_last($schedule)][6]);
    }

    public function test_schedule_calculation(): void
    {
        $equalInstallments = new EqualInstallments(
            date: Carbon::create(2023, 1),
            credit: $this->credit
        );

        $schedule = ScheduleFormatter::format($equalInstallments->schedule()->get());

        $this->assertEquals(31, $schedule[0][1]);
        $this->assertEquals(300000, $schedule[0][2]);
        $this->assertEquals(2221.81, $schedule[0][3]);
        $this->assertEquals(423.58, $schedule[0][4]);
        $this->assertEquals(2645.39, $schedule[0][5]);
        $this->assertEquals(299576.42, $schedule[0][6]);

        $this->assertEquals(31, $schedule[array_key_last($schedule)][1]);
        $this->assertEquals(2333.63, $schedule[array_key_last($schedule)][2]);
        $this->assertEquals(17.28, $schedule[array_key_last($schedule)][3]);
        $this->assertEquals(2333.63, $schedule[array_key_last($schedule)][4]);
        $this->assertEquals(2350.91, $schedule[array_key_last($schedule)][5]);
        $this->assertEquals(0, $schedule[array_key_last($schedule)][6]);
    }

    public function test_schedule_overpayment_shorter_period(): void
    {
        $equalInstallments = new EqualInstallments(
            date: Carbon::create(2023, 1),
            credit: $this->credit,
            overpayments: $this->overpayments
        );

        $schedule = ScheduleFormatter::format($equalInstallments->scheduleShorterPeriod()->get());

        $this->assertEquals(300000, $schedule[0][2]);
        $this->assertEquals(2221.81, $schedule[0][3]);
        $this->assertEquals(423.58, $schedule[0][4]);
        $this->assertEquals(2645.39, $schedule[0][5]);
        $this->assertEquals(299576.42, $schedule[0][6]);

        $this->assertEquals(1992.80, $schedule[220][2]);
        $this->assertEquals(14.76, $schedule[220][3]);
        $this->assertEquals(1992.80, $schedule[220][4]);
        $this->assertEquals(2007.56, $schedule[220][5]);
        $this->assertEquals(0, $schedule[220][6]);
    }

    public function test_schedule_overpayment_smaller_installment(): void
    {
        $equalInstallments = new EqualInstallments(
            date: Carbon::create(2023, 1),
            credit: $this->credit,
            overpayments: $this->overpayments
        );

        $schedule = ScheduleFormatter::format($equalInstallments->scheduleSmallerInstallment()->get());

        $this->assertEquals(300000, $schedule[0][2]);
        $this->assertEquals(2221.81, $schedule[0][3]);
        $this->assertEquals(423.58, $schedule[0][4]);
        $this->assertEquals(2645.39, $schedule[0][5]);
        $this->assertEquals(299576.42, $schedule[0][6]);

        $this->assertEquals(2614.62, $schedule[array_key_last($schedule)][2]);
        $this->assertEquals(19.36, $schedule[array_key_last($schedule)][3]);
        $this->assertEquals(2517.56, $schedule[array_key_last($schedule)][4]);
        $this->assertEquals(2633.98, $schedule[array_key_last($schedule)][5]);
        $this->assertEquals(0, $schedule[array_key_last($schedule)][6]);
    }
}
