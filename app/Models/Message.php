<?php

namespace App\Models;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use BroadcastsEvents, HasFactory;


    protected $primaryKey = 'message_id';
    protected $fillable = ['detail', 'status', 'sender_id', 'receiver_id'];

    public function from() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function to() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function broadcastOn($event)
    {
        return new PrivateChannel('MessageChannel.User.'.$this->receiver_id); // Private Channel
        // return new Channel('MessageChannel.User.'.$this->receiver_id); // Public Channel
    }
}