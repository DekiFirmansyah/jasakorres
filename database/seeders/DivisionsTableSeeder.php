<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Division;

class DivisionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            [
                'name' => 'Finance',
            ],
            [
                'name' => 'Human Capital & General Affair',
            ],
            [
                'name' => 'Operation',
            ],
            [
                'name' => 'Maintenance',
            ],
            [
                'name' => 'Direksi',
            ],
        ];

        Division::insert($divisions);
    }
}