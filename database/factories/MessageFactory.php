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
        $sender_id = User::all()->random()->user_id;
        $receiver_id = User::all()->random()->user_id;
        $title_list = ['report', 'chat'];
        $status_list = ['hiden', 'unseen', 'seen'];
        return [
            'title' => Arr::random($title_list),
            'detail' => $this->faker->text(200),
            'status'=> Arr::random($status_list),
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id
        ];
    }
}
