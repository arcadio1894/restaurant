<?php

namespace Database\Seeders;

use App\Models\Milestone;
use Illuminate\Database\Seeder;

class DataRewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Milestone::create([
            'flames' => 25,
            'title' => 'Personaliza tu bebida',
            'description' => 'Añádele a tu bebida un shot de café, un topping o un syrup, o cambia de leche animal por bebida vegetal',
            'image' => '25.svg'
        ]);

        Milestone::create([
            'flames' => 50,
            'title' => 'Desde café del día hasta croissants',
            'description' => 'Disfruta de café del día, galletas, muffins, kekes, croissants de mantequilla y más',
            'image' => '50.svg'
        ]);

        Milestone::create([
            'flames' => 100,
            'title' => 'Tu bebida favorita te espera',
            'description' => 'Comienza tu mañana con tu bebida favorita, Refresher, Cold Brew o hasta un delicioso Frappuccino',
            'image' => '100.svg'
        ]);

        Milestone::create([
            'flames' => 200,
            'title' => 'Sándwiches, wraps, postres y más',
            'description' => 'Nutre tu día junto a un sandwich, wrap o postre de tu elección',
            'image' => '200.svg'
        ]);

        Milestone::create([
            'flames' => 300,
            'title' => 'Tu merch favorito o café en grano',
            'description' => "Llévate a casa la taza o tumbler que siempre quisiste o el café en grano que más te gusta. <br><br>* Canje válido para modelos de merchandising seleccionados. No válido para artículos de filtrado de café ni tazas de edición Collector.",
            'image' => '300.svg'
        ]);
    }
}
