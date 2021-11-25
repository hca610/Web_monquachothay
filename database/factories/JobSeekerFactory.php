<?php

namespace Database\Factories;

use App\Models\JobSeeker;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class JobSeekerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JobSeeker::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->unique()->numberBetween(1,35),
            'birthday' => $this->faker->date(),
            'gender' => 'male',
            'qualification' => $this->faker->text(20),
            'work_experience' => $this->faker->text(20),
            'education' => $this->faker->text(20),
            'skill' => $this->faker->text(20),
        ];
    }
}
