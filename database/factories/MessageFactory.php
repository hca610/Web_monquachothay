<?php

namespace Database\Factories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Arr;

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
        $allTitle = array("report", "chat");
        $allStatus = array("hidden", "unhide");
        $sender_id = User::all()->random()->user_id;
        $receiver_id = User::all()->random()->user_id;
        return [
            'title' => Arr::random($allTitle),
            'detail' => $this->faker->text(200),
            'status'=> Arr::random($allStatus),
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id
        ];
    }
}
