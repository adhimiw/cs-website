<?php

namespace Database\Seeders;

use App\Models\User;
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
        // Create Default Admin User
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@climbsphere.com'],
            [
                'name' => 'ClimbSphere Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );

        $this->call([
            SiteContentSeeder::class,
        ]);
    }
}
