<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['name' => 'Familiar', 'size' => '35cm', 'price' => 35.00],
            ['name' => 'Grande', 'size' => '30cm', 'price' => 31.00],
            ['name' => 'Mediana', 'size' => '20cm', 'price' => 22.00],
        ];

        foreach ($types as $type) {
            Type::create($type);
        }
    }
}
