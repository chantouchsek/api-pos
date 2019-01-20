<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'email_verified_at' => now(),
            'password' => bcrypt('secret'),
            'remember_token' => str_random(10),
            'username' => 'admin.user',
            'gender' => 1,
            'date_of_birth' => '2014-09-23',
            'address' => 'Phnom Penh',
            'active' => true,
            'phone_number' => '093234923',
            'locale' => 'en',
            'birth_place' => 'Phnom Penh'
        ]);
        $user->assignRole('Supper Admin');
        factory(\App\Models\User::class, 5)->create();
    }
}
