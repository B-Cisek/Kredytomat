<?php

namespace App\Spiders;

use Generator;
use RoachPHP\Downloader\Middleware\RequestDeduplicationMiddleware;
use RoachPHP\Extensions\LoggerExtension;
use RoachPHP\Extensions\StatsCollectorExtension;
use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;

class WiborSpider extends BasicSpider
{
    public array $startUrls = [
        'https://wibor.money.pl/'
    ];

    public array $downloaderMiddleware = [
        RequestDeduplicationMiddleware::class,
    ];

    public array $spiderMiddleware = [
        //
    ];

    public array $itemProcessors = [
        SaveWiborToDatabaseProcessor::class
    ];

    public array $extensions = [
        LoggerExtension::class,
        StatsCollectorExtension::class,
    ];

    public int $concurrency = 2;

    public int $requestDelay = 1;

    /**
     * @param Response $response
     * @return Generator
     */
    public function parse(Response $response): Generator
    {
        $m1 = $response->filter('table > tbody > tr:nth-child(5) > td:nth-child(2)')->text();
        $m3 = $response->filter('table > tbody > tr:nth-child(6) > td:nth-child(2)')->text();
        $m6 = $response->filter('table > tbody > tr:nth-child(7) > td:nth-child(2)')->text();

        yield $this->item([
            'm1' => $m1,
            'm3' => $m3,
            'm6' => $m6,
        ]);
    }
}
