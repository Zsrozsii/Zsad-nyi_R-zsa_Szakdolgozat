<?php

namespace Database\Seeders;

use App\Models\Drink;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DrinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Drink::create([
            'id' => '1',
            'name' => 'water',
            'default_ml' => 200,
            'sugar' => 0,
            'caffeine' => 0,
            'effectiveness' => 1.0,
        ]);

        Drink::insert([
            [
                'name' => 'coke',
                'default_ml' => 200,
                'sugar' => 11,
                'caffeine' => 9.7,
                'effectiveness' => 0.98,
            ],
            [
                'name' => 'espresso',
                'default_ml' => 40,
                'sugar' => 0,
                'caffeine' => 212,
                'effectiveness' => 0.98,
            ],
            [
                'name' => 'Monster energy',
                'default_ml' => 500,
                'sugar' => 11,
                'caffeine' => 32,
                'effectiveness' => 0.98,
            ],

        ]);
    }
}
