<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\Category::factory(10)->create();
        // \App\Models\User::factory(5)->create();
        // \App\Models\JobSeeker::factory(25)->create();
        \App\Models\Employer::factory(5)->create();
    }
}
