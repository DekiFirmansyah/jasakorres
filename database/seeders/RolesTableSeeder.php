<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'guard_name' => 'web'
            ],
            [
                'name' => 'secretary',
                'guard_name' => 'web'
            ],
            [
                'name' => 'officer',
                'guard_name' => 'web'
            ],
            [
                'name' => 'manager',
                'guard_name' => 'web'
            ],
            [
                'name' => 'general-manager',
                'guard_name' => 'web'
            ],
            [
                'name' => 'general-director',
                'guard_name' => 'web'
            ],
            [
                'name' => 'executive-director',
                'guard_name' => 'web'
            ],
        ];

        Role::insert($roles);
    }
}