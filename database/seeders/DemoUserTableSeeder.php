<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_role = Role::where('name', 'Administrator')->first()->id;

        User::create([
            'id' => 1,
            'name' => 'Demo',
            'pre_name' => 'Demo',
            'last_name' => 'User',
            'email' => 'demo@user.com',
            'password' => Hash::make('demo'),
            'email_verified_at' => now(),
            'subscribed_newsletter' => 1,
            'role_id' => $admin_role
        ]);
    }
}
