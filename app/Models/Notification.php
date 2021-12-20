<?php

namespace App\Models;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use BroadcastsEvents, HasFactory;

    protected $primaryKey = "notification_id";
    protected $fillable = ['title', 'detail', 'status', 'receiver_id'];

    public function receiver(){
        return $this->belongsTo(User::class, 'receiver_id', 'user_id');
    }

    public function broadcastOn($event)
    {
        return new PrivateChannel('NotificationChannel.User.'.$this->receiver_id);
        // return [new PrivateChannel('NotificationChannel.User.'.$this->receiver_id), new Channel('NotificationChannel.User.'.$this->receiver_id)];
    }
}
