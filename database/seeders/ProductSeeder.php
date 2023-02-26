<?php

namespace Database\Seeders;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate Expert Attribute
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('products')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = Carbon::now();
        // Insert pre-defined attributes
        DB::table('products')->insert([
            [ //1
                'name' => 'Piattos',
                'price' => 25,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [ //2
                'name' => 'Nova',
                'price' => 25,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [ //3
                'name' => 'Cracklings',
                'price' => 20,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [ //4
                'name' => 'Hanny',
                'price' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [ //5
                'name' => 'Pancit Canton - Original',
                'price' => 15,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [ //6
                'name' => 'Pancit Canton - Sweet and Spicy',
                'price' => 15,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [ //7
                'name' => 'Pancit Canton - Calamansi',
                'price' => 15,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [ //8
                'name' => 'Pancit Canton - Chilimansi',
                'price' => 15,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [ //9
                'name' => 'Datu Puti - Toyo',
                'price' => 16,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [ //10
                'name' => 'Datu Puti - Suka',
                'price' => 16,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [ //11
                'name' => 'Sunshine - Green Peas',
                'price' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [ //12
                'name' => 'Mang Tomas - Original',
                'price' => 35,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [ //13
                'name' => 'Mang Tomas - Siga',
                'price' => 35,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [ //14
                'name' => 'Coca Cola 1.5L',
                'price' => 75,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [ //15
                'name' => 'Royal 1.5L',
                'price' => 75,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [ //16
                'name' => 'Sprite 1.5L',
                'price' => 75,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
