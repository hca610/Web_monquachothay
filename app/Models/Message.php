<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;


    protected $primaryKey = 'message_id';
    protected $fillable = ['type', 'detail', 'status', 'sender_id', 'receiver_id'];

    public function from() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function to() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
