<?php

namespace Database\Seeders;

use App\Models\Topping;
use Illuminate\Database\Seeder;

class DataToppingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Topping::create([
            'name' => 'Jamón',
            'type' => 'meat',
            'price_exception' => 0,
            'price_extra' => 1,
            'stateActive' => 1,
            'image' => 'jamon.png',
            'slug' => 'Jamon'
        ]);
        Topping::create([
            'name' => 'Chorizo',
            'type' => 'meat',
            'price_exception' => 0,
            'price_extra' => 1,
            'stateActive' => 1,
            'image' => 'chorizo.png',
            'slug' => 'Chorizo'
        ]);
        Topping::create([
            'name' => 'Pepperoni',
            'type' => 'meat',
            'price_exception' => 1,
            'price_extra' => 1,
            'stateActive' => 1,
            'image' => 'pepperoni.png',
            'slug' => 'Pepperoni'
        ]);
        Topping::create([
            'name' => 'Lomo',
            'type' => 'meat',
            'price_exception' => 4,
            'price_extra' => 1,
            'stateActive' => 1,
            'image' => 'lomo.png',
            'slug' => 'Lomo'
        ]);

        Topping::create([
            'name' => 'Albahaca',
            'type' => 'veggie',
            'price_exception' => 0,
            'price_extra' => 1,
            'stateActive' => 1,
            'image' => 'albahaca.png',
            'slug' => 'Albahaca'
        ]);
        Topping::create([
            'name' => 'Aceituna verde',
            'type' => 'veggie',
            'price_exception' => 0,
            'price_extra' => 1,
            'stateActive' => 1,
            'image' => 'aceitunaVerde.png',
            'slug' => 'AceitunaVerde'
        ]);
        Topping::create([
            'name' => 'Champiñones',
            'type' => 'veggie',
            'price_exception' => 0,
            'price_extra' => 1,
            'stateActive' => 1,
            'image' => 'champinones.png',
            'slug' => 'Champinones'
        ]);
        Topping::create([
            'name' => 'Piña',
            'type' => 'veggie',
            'price_exception' => 0,
            'price_extra' => 1,
            'stateActive' => 1,
            'image' => 'pina.png',
            'slug' => 'Pina'
        ]);
        Topping::create([
            'name' => 'Durazno',
            'type' => 'veggie',
            'price_exception' => 0,
            'price_extra' => 1,
            'stateActive' => 1,
            'image' => 'durazno.png',
            'slug' => 'Durazno'
        ]);
        Topping::create([
            'name' => 'Cebolla caramelizada',
            'type' => 'veggie',
            'price_exception' => 0,
            'price_extra' => 1,
            'stateActive' => 1,
            'image' => 'cebolla.png',
            'slug' => 'Cebolla'
        ]);

        Topping::create([
            'name' => 'Queso',
            'type' => 'special',
            'price_exception' => 0,
            'price_extra' => 1,
            'stateActive' => 1,
            'image' => 'queso.png',
            'slug' => 'Queso'
        ]);

        Topping::create([
            'name' => 'Salsa',
            'type' => 'special',
            'price_exception' => 0,
            'price_extra' => 1,
            'stateActive' => 1,
            'image' => 'salsa-de-tomate.png',
            'slug' => 'Salsa'
        ]);
    }
}
