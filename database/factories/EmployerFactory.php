<?php

namespace Database\Factories;

use App\Models\Employer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::all()->random()->user_id,
            'about_us' => $this->faker->text(200),
            'image_link' => $this->faker->imageUrl(),
            'num_employee' => $this->faker->randomNumber(),
            'category_id' => $this->faker->randomNumber(),
        ];
    }
}
