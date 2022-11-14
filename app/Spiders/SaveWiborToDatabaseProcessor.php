<?php

namespace App\Spiders;

use App\Enums\WiborType;
use App\Models\Wibor;
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
            $value = str_replace(',','.', $value);
            $key = strtoupper(strrev($key));

            Wibor::where('type', $key)
                ->update([
                    'value' => floatval($value)
                ]);
        }

        return $item;
    }
}
