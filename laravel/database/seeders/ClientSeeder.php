<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('clients')->insert([
            [
                'name' => 'João da Silva',
                'email' => 'joao@example.com',
            ],
            [
                'name' => 'Maria Oliveira',
                'email' => 'maria@example.com',
            ],
            [
                'name' => 'Carlos Santos',
                'email' => 'carlos@example.com',
            ],
        ]);
    }
}
