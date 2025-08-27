<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OccupationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $occupations = [
            ['name' => 'Contador'],
            ['name' => 'Ingeniero de Sistemas'],
            ['name' => 'Abogado'],
            ['name' => 'Médico'],
            ['name' => 'Profesor'],
            ['name' => 'Enfermero'],
            ['name' => 'Electricista'],
            ['name' => 'Panadero'],
            ['name' => 'Carpintero'],
            ['name' => 'Policía'],
            ['name' => 'Soldador'],
            ['name' => 'Vendedor'],
            ['name' => 'Taxista'],
            ['name' => 'Mecánico'],
            ['name' => 'Cajero'],
            ['name' => 'Chef'],
            ['name' => 'Albañil'],
            ['name' => 'Chofer'],
            ['name' => 'Psicólogo'],
            ['name' => 'Farmacéutico'],
            ['name' => 'Recepcionista'],
            ['name' => 'Estilista'],
            ['name' => 'Diseñador Gráfico'],
            ['name' => 'Periodista'],
            ['name' => 'Contador Público'],
            ['name' => 'Obrero'],
            ['name' => 'Ingeniero Civil'],
            ['name' => 'Técnico en Computación'],
            ['name' => 'Auxiliar Administrativo'],
            ['name' => 'Gestor Comercial'],
        ];

        DB::table('occupations')->insert($occupations);
    }
}
