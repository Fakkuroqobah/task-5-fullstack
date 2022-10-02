<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Admin',
                'email' => 'admin@mail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Company',
                'email' => 'company@mail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'User',
                'email' => 'user@mail.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
            ],
        ];

        foreach ($data as $row) {
            $data = User::create($row);
        }
    }
}
