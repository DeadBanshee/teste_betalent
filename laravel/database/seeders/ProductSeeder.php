<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Notebook Dell Inspiron',
                'amount' => 4500,
            ],
            [
                'name' => 'Mouse Logitech M720',
                'amount' => 250,
            ],
            [
                'name' => 'Teclado Mecânico Redragon Kumara',
                'amount' => 350,
            ],
            [
                'name' => 'Monitor LG Ultrawide 29"',
                'amount' => 1200,
            ],
        ]);
    }
}
