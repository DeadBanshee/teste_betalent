<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GatewaySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('gateways')->insert([
            [
                'name' => 'gateway_1',
                'is_active' => true,
                'priority' => 1,
                'url' => 'http://localhost:3001/api/payments',
            ],
            [
                'name' => 'gateway_2',
                'is_active' => true,
                'priority' => 2,
                'url' => 'http://localhost:3002/api/payments',
            ],
        ]);
    }
}
