<?php

namespace Database\Factories;

use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Arr;

use CreateReportsTable;

class ReportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Report::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'detail' => $this->faker->text(200),
            'status'=> Arr::random(CreateReportsTable::$status_list),
            'sender_id' => User::all()->random()->user_id,
            'receiver_id' => User::all()->random()->user_id
        ];
    }
}
