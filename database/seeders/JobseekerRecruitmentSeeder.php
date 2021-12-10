<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;


class JobseekerRecruitmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public static function run()
    {
        $typeList = ['pending', 'reviewed', 'hired', 'rejected'];

        for ($i = 0; $i < 30; $i++) {
            DB::table('job_seeker_recruitment')->insert(array(
                'job_seeker_id' => rand(1, 35),
                'recruitment_id' => rand(1, 30),
                'type' => Arr::random($typeList),
                'following' => rand(0, 1),
            ));
        }
    }
}
