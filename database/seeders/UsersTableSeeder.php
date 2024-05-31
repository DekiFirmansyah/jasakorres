<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserDetail;
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
            'name' => 'Administrator',
            'username' => 'administrator',
            'email' => 'administrator@jpt.id',
            'password' => bcrypt('administrator'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        $admin->assignRole('admin');

        $secretary = User::create([
            'name' => 'Auzi T. S',
            'username' => 'Auzits',
            'email' => 'auzi@jpt.id',
            'password' => bcrypt('auzi11'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $secretary->assignRole('secretary');

        $officer = User::create([
            'name' => 'Rendy H. P',
            'username' => 'rendyhp',
            'email' => 'rendy@jpt.id',
            'password' => bcrypt('rendy11'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $officer->assignRole('officer');

        $manager = User::create([
            'name' => 'Brahmo W. S',
            'username' => 'brahmows',
            'email' => 'brahmo@jpt.id',
            'password' => bcrypt('brahmo11'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $manager->assignRole('manager');

        $general_manager = User::create([
            'name' => 'Agus Priyanto',
            'username' => 'aguspriyanto',
            'email' => 'agus@jpt.id',
            'password' => bcrypt('agus11'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $general_manager->assignRole('general-manager');
        
        $general_director = User::create([
            'name' => 'Indarani',
            'username' => 'indarani',
            'email' => 'indarani@jpt.id',
            'password' => bcrypt('indarani11'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $general_director->assignRole('general-director');

        $executive_director = User::create([
            'name' => 'Netty Renova',
            'username' => 'nettyrenova',
            'email' => 'netty@jpt.id',
            'password' => bcrypt('netty11'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $executive_director->assignRole('executive-director');

        $userDetails = [
            [
                'nip' => '11111',
                'jabatan' => 'Administrator',
                'user_id' => $admin->id,
                'division_id' => 2,
            ],
            [
                'nip' => '10054',
                'jabatan' => 'Secretary (SO)',
                'user_id' => $secretary->id,
                'division_id' => 2,
            ],
            [
                'nip' => '10034',
                'jabatan' => 'Human Capital Senior Officer',
                'user_id' => $officer->id,
                'division_id' => 2,
            ],
            [
                'nip' => '10023',
                'jabatan' => 'Human Capital & General Affair Manager',
                'user_id' => $manager->id,
                'division_id' => 2,
            ],
            [
                'nip' => '10044',
                'jabatan' => 'General Manager Finance & HCGA',
                'user_id' => $general_manager->id,
                'division_id' => 2,
            ],
            [
                'nip' => '10025',
                'jabatan' => 'Direktur Keuangan & Umum',
                'user_id' => $general_director->id,
                'division_id' => 5,
            ],
            [
                'nip' => '10066',
                'jabatan' => 'Direktur Utama',
                'user_id' => $executive_director->id,
                'division_id' => 5,
            ],
        ];

        UserDetail::insert($userDetails);
    }
}