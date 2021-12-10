<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    
    protected $primaryKey = 'report_id';
    protected $fillable = ['detail', 'status', 'sender_id', 'receiver_id'];

    public function from() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function to() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
