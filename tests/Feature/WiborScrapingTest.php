<?php

namespace Tests\Feature;

use App\Services\WiborService;
use Tests\TestCase;

class WiborScrapingTest extends TestCase
{
    public function test_format_scraped_wibor(): void
    {
        $wiborService = new WiborService();

        $result = $wiborService->formatScrapedWibor('5,01', 'm1');

        $this->assertEquals([
            'key' => '1M',
            'value' => '5.01'
        ], $result);
    }
}
