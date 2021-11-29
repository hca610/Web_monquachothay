<?php

namespace Database\Factories;

use App\Models\Employer;
use App\Models\Recruitment;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecruitmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Recruitment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category' => $this->faker->text(10),
            'employer_id' => Employer::all()->random()->employer_id,
            'min_salary' => $this->faker->numberBetween(1,10)*1000000,
            'job_name' => $this->faker->jobTitle(),
            'detail' => $this->faker->realText(),
            'requirement' => $this->faker->realText(),
            'address' => $this->faker->address(),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
        ];
    }
}
