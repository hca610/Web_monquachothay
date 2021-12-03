<?php

namespace Database\Factories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Arr;

use CreateMessagesTable;

class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => Arr::random(CreateMessagesTable::$type_list),
            'detail' => $this->faker->text(200),
            'status'=> Arr::random(CreateMessagesTable::$status_list),
            'sender_id' => User::all()->random()->user_id,
            'receiver_id' => User::all()->random()->user_id
        ];
    }
}
