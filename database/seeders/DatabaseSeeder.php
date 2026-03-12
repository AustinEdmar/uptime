<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Site;
use App\Models\Endpoint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Austin Edmar',
            'email' => 'austin@gmail.com',
            'password' => bcrypt('923.eddy'),
        ]);

        Site::create([
            'user_id' => 1,
            'scheme' => 'https',
            'domain' => 'google.com',
        ]);

        Endpoint::create([
            'site_id' => 1,
            'location' => '/',
            'frequency' => 60,
            'next_check' => now()->addSeconds(60),
        ]);

        
    }
}
