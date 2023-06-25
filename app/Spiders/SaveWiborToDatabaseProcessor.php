<?php

namespace App\Spiders;

use App\Models\Wibor;
use App\Services\WiborService;
use RoachPHP\ItemPipeline\ItemInterface;
use RoachPHP\ItemPipeline\Processors\ItemProcessorInterface;
use RoachPHP\Support\Configurable;

class SaveWiborToDatabaseProcessor implements ItemProcessorInterface
{
    use Configurable;

    public function processItem(ItemInterface $item): ItemInterface
    {
        $wibors = $item->all();

        foreach ($wibors as $key => $value) {
            (new WiborService())->update($value, $key);
        }

        return $item;
    }
}
