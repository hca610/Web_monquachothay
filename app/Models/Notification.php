<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $primaryKey = "notification_id";
    protected $fillable = ['title', 'detail', 'status', 'receiver_id'];

    public function receiver(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
