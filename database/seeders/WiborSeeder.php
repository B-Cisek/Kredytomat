<?php

namespace Database\Seeders;

use App\Models\Wibor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WiborSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $wibors = [
            [
                'id' => 1,
                'type' => '1M',
                'value' => 7
            ],
            [
                'id' => 2,
                'type' => '3M',
                'value' => 7.33
            ],
            [
                'id' => 3,
                'type' => '6M',
                'value' => 7.55
            ]
        ];

        foreach ($wibors as $wibor) {
            Wibor::create($wibor);
        }
    }
}
