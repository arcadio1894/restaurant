<?php

namespace Database\Seeders;

use App\Models\DataGeneral;
use Illuminate\Database\Seeder;

class DataGeneralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DataGeneral::create([
            'name' => 'open_time',
            'valueText' => '6:30',
            'valueNumber' => 0,
            'description' => 'Horario de inicio de atención'
        ]);

        DataGeneral::create([
            'name' => 'close_time',
            'valueText' => '23:30',
            'valueNumber' => 0,
            'description' => 'Horario de término de atención'
        ]);
    }
}
