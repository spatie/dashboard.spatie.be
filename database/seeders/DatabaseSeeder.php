<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::truncate();

        User::create([
            'name' => 'dashboard user',
            'email' => 'info@spatie.be',
            'password' => bcrypt('secret'),
        ]);
    }
}
