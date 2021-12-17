<?php

use Illuminate\Support\Facades\Broadcast;

use App\Broadcasting\MessageChannel;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('MessageChannel.User.{userId}', function ($user, $userId) {
    return (int) $user->user_id === (int) $userId;
});

Broadcast::channel('NotificationChannel.User.{userId}', function ($user, $userId) {
    return true;
    return (int) $user->user_id === (int) $userId;
});