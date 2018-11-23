<?php

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
