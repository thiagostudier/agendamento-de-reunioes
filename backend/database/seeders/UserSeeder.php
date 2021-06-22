<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        $user = User::insert([
            'name' => 'Jorge Piperuner',
            'email' => 'jorge@piperun.com',
            'password' => bcrypt('password'),
        ]);

    }
}
