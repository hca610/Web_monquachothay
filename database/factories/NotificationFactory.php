<?php

namespace Database\Factories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Arr;

use CreateNotificationsTable;

class NotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Notification::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->text(20),
            'detail' => $this->faker->text(200),
            'status'=> Arr::random(CreateNotificationsTable::$status_list),
            'receiver_id' => User::all()->random()->user_id
        ];
    }
}
