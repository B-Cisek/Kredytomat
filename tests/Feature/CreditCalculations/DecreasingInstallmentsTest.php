<?php

namespace CreditCalculations;

use App\Services\CreditCalculations\Credit;
use App\Services\CreditCalculations\DecreasingInstallments;
use App\Services\CreditCalculations\Enum\CommissionType;
use App\Services\CreditCalculations\Enum\PeriodType;
use App\Services\CreditCalculations\ScheduleFormatter;
use Carbon\Carbon;
use Tests\TestCase;

class DecreasingInstallmentsTest extends TestCase
{
    private Credit $credit;
    private array  $overpayments;

    public function __construct()
    {
        parent::__construct();

        $this->credit = new Credit(
            amountOfCredit: 400_000,
            period: 20,
            periodType: PeriodType::YEAR,
            margin: 5.66,
            wibor: 1,
            commission: 0,
            commissionType: CommissionType::NUMBER
        );

        $this->overpayments = [
            [
                'date' => [
                    'start' => [
                        'month' => 12,
                        'year' => 2023
                    ],
                    'end' => [
                        'month' => 12,
                        'year' => 2023
                    ]
                ],
                'value' => 50_000
            ],
            [
                'date' => [
                    'start' => [
                        'month' => 12,
                        'year' => 2025
                    ],
                    'end' => [
                        'month' => 12,
                        'year' => 2025
                    ]
                ],
                'value' => 35_500
            ],
        ];
    }

    public function test_schedule_calculation(): void
    {
        $decreasingInstallment = new DecreasingInstallments(
            Carbon::create(2023, 8),
            $this->credit
        );

        $schedule = ScheduleFormatter::format($decreasingInstallment->schedule()->get());

        $this->assertEquals(400000, $schedule[0][2]);
        $this->assertEquals(2262.58, $schedule[0][3]);
        $this->assertEquals(1666.67, $schedule[0][4]);
        $this->assertEquals(3929.24, $schedule[0][5]);
        $this->assertEquals(398333.33, $schedule[0][6]);

        $this->assertEquals(1666.67, $schedule[array_key_last($schedule)][2]);
        $this->assertEquals(9.43, $schedule[array_key_last($schedule)][3]);
        $this->assertEquals(1666.67, $schedule[array_key_last($schedule)][4]);
        $this->assertEquals(1676.09, $schedule[array_key_last($schedule)][5]);
        $this->assertEquals(0, $schedule[array_key_last($schedule)][6]);
    }

    public function test_schedule_with_interest_change(): void
    {
        $interestsRateChanges = [
            [
                'date' => [
                    'month' => 1,
                    'year' => 2026
                ],
                'value' => 5
            ]
        ];

        $decreasingInstallment = new DecreasingInstallments(
            date: Carbon::create(2023, 8),
            credit: $this->credit,
            interestsRateChanges: $interestsRateChanges
        );

        $schedule = ScheduleFormatter::format($decreasingInstallment->schedule()->get());

        $this->assertEquals(400000, $schedule[0][2]);
        $this->assertEquals(2262.58, $schedule[0][3]);
        $this->assertEquals(1666.67, $schedule[0][4]);
        $this->assertEquals(3929.24, $schedule[0][5]);
        $this->assertEquals(398333.33, $schedule[0][6]);

        $this->assertEquals(1666.67, $schedule[array_key_last($schedule)][2]);
        $this->assertEquals(7.08, $schedule[array_key_last($schedule)][3]);
        $this->assertEquals(1666.67, $schedule[array_key_last($schedule)][4]);
        $this->assertEquals(1673.74, $schedule[array_key_last($schedule)][5]);
        $this->assertEquals(0, $schedule[array_key_last($schedule)][6]);
    }

    public function test_schedule_overpayment_shorter_period(): void
    {
        $decreasingInstallment = new DecreasingInstallments(
            date: Carbon::create(2023, 8),
            credit: $this->credit,
            overpayments: $this->overpayments
        );

        $schedule = ScheduleFormatter::format($decreasingInstallment->scheduleShorterPeriod()->get());

        $this->assertCount(189, $schedule);

        $this->assertEquals(400000, $schedule[0][2]);
        $this->assertEquals(2262.58, $schedule[0][3]);
        $this->assertEquals(1666.67, $schedule[0][4]);
        $this->assertEquals(3929.24, $schedule[0][5]);
        $this->assertEquals(398333.33, $schedule[0][6]);

        $this->assertEquals(1166.67, $schedule[array_key_last($schedule)][2]);
        $this->assertEquals(6.39, $schedule[array_key_last($schedule)][3]);
        $this->assertEquals(1166.67, $schedule[array_key_last($schedule)][4]);
        $this->assertEquals(1673.05, $schedule[array_key_last($schedule)][5]);
        $this->assertEquals(0, $schedule[array_key_last($schedule)][6]);
    }

    public function test_schedule_overpayment_smaller_installment(): void
    {
        $decreasingInstallment = new DecreasingInstallments(
            date: Carbon::create(2023, 8),
            credit: $this->credit,
            overpayments: $this->overpayments
        );

        $schedule = ScheduleFormatter::format($decreasingInstallment->scheduleSmallerInstallment()->get());

        $this->assertEquals(400000, $schedule[0][2]);
        $this->assertEquals(2262.58, $schedule[0][3]);
        $this->assertEquals(1666.67, $schedule[0][4]);
        $this->assertEquals(3929.24, $schedule[0][5]);
        $this->assertEquals(398333.33, $schedule[0][6]);

        $this->assertEquals(1287.35, $schedule[array_key_last($schedule)][2]);
        $this->assertEquals(7.28, $schedule[array_key_last($schedule)][3]);
        $this->assertEquals(1287.35, $schedule[array_key_last($schedule)][4]);
        $this->assertEquals(1294.63, $schedule[array_key_last($schedule)][5]);
        $this->assertEquals(0, $schedule[array_key_last($schedule)][6]);
    }
}
