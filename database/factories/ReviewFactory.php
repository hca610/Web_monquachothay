<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Arr;

use CreateReviewsTable;

class ReviewFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Review::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'detail' => $this->faker->text(200),
            'status'=> Arr::random(CreateReviewsTable::$status_list),
            'sender_id' => User::all()->random()->user_id,
            'receiver_id' => User::all()->random()->user_id
        ];
    }
}
