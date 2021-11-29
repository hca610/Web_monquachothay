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
        \App\Models\User::factory(35)->create(['role' => 'jobseeker']);
        \App\Models\JobSeeker::factory(35)->create();
        \App\Models\User::factory(15)->create(['role' => 'employer']);
        \App\Models\Employer::factory(15)->create();
        \App\Models\Recruitment::factory(30)->create();

        \App\Models\Message::factory(50)->create();
        \App\Models\Notification::factory(50)->create();
    }
}
