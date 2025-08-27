<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ZonesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zones = [
            ['name' => 'PROGRESO'],
            ['name' => 'COCHARCAS'],
            ['name' => 'LIBRE CENTRAL'],
            ['name' => '15 DE SETIEMBRE'],
            ['name' => 'HUAMANTANGA'],
            ['name' => 'PARIAHUANCA'],
        ];

        DB::table('zones')->insert($zones);
    }
}
