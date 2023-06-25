<?php

namespace App\Services;

use App\Models\Wibor;

class WiborService
{
    public function update(string $value, string $key): void
    {
        $data = $this->formatScrapedWibor($value, $key);
        Wibor::updateWibor($data['key'], $data['value']);
    }

    public function formatScrapedWibor(string $value, string $key): array
    {
        $value = str_replace(',', '.', $value);
        $key = strtoupper(strrev($key));

        return [
            'key' => $key,
            'value' => $value
        ];
    }
}
