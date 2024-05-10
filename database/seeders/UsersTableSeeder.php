<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin = User::create([
            'name' => 'Admin',
            'username' => '11111',
            'email' => 'admin@jpt.id',
            'password' => bcrypt('admin'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $admin->assignRole('admin');

        $secretary = User::create([
            'name' => 'Secretary',
            'username' => '10022',
            'email' => 'secretary@jpt.id',
            'password' => bcrypt('secretary'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $secretary->assignRole('secretary');

        $staff = User::create([
            'name' => 'Staff',
            'username' => '10033',
            'email' => 'staff@jpt.id',
            'password' => bcrypt('staff'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $staff->assignRole('staff');

        $manager = User::create([
            'name' => 'Manager',
            'username' => '10044',
            'email' => 'manager@jpt.id',
            'password' => bcrypt('manager'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $manager->assignRole('manager');

        $director = User::create([
            'name' => 'Director',
            'username' => '10055',
            'email' => 'director@jpt.id',
            'password' => bcrypt('director'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $director->assignRole('director');
    }
}