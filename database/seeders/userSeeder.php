<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            ['email'=> 'admin@tecvalles.mx','name' => 'admin', 'password' => bcrypt('12345678')]
        ];

        foreach($users as $user){
            User::create($user);
        }

        $this->command->info('Usuarios creados');
    }
}
