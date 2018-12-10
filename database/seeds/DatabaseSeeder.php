<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::truncate();

        User::create([
            'name' => 'dashboard user',
            'email' => 'info@spatie.be',
            'password' => bcrypt('secret'),
        ]);
    }
}
