<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;


    protected $primaryKey = 'review_id';
    protected $fillable = ['detail', 'status', 'sender_id', 'receiver_id'];

    public function sender() {
        return $this->belongsTo(User::class, 'sender_id', 'user_id');
    }

    public function receiver() {
        return $this->belongsTo(User::class, 'receiver_id', 'user_id');
    }
}
