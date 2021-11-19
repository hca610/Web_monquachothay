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
        // \App\Models\Category::factory(50)->create();
        \App\Models\User::factory(25)->create();
        // \App\Models\JobSeeker::factory(50)->create();
        // \App\Models\Employer::factory(50)->create();
        \App\Models\Message::factory(50)->create();
        \App\Models\Notification::factory(50)->create();
    }
}
