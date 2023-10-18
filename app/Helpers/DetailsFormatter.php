<?php

declare(strict_types=1);

namespace App\Helpers;

class DetailsFormatter
{
    public static function format(string $details): string
    {
        $result = [];
        $lines = explode(';', $details);


        foreach ($lines as $line) {
            if (empty($line)) {
                continue;
            }
            $line = str_replace('\n', '', $line);
            $detail = explode(':', $line);
            $key = trim($detail[0]);
            $value = trim($detail[1]);
            $result[$key] = $value;
        }

        return json_encode($result);
    }
}
