<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
            [
                'name' => 'User',
                'color' => '242424',
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Moderator',
                'color' => '0d8028',
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Administrator',
                'color' => '800d0d',
                'is_default' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
