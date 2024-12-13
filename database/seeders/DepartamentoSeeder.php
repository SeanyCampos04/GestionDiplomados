<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Departamento;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departamentos = [
            ['name' => 'Ciencias Basicas'],
            ['name' => 'Ciencias Economico - Administrativas'],
            ['name' => 'Sistema y computacion'],
            ['name' => 'Industrial'],
            ['name' => 'Ingenierias'],
            ['name' => 'Agronomia']
        ];

        foreach($departamentos as $departamento){
            Departamento::create($departamento);
        }

        $this->command->info('Departamentos creados');
    }
}
