<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

<<<<<<< HEAD:app/Models/Review.php

    protected $primaryKey = 'review_id';
=======
    protected $primaryKey = 'report_id';
>>>>>>> f46544b38b576042a1804d6c02a4ac1a3d026b64:app/Models/Report.php
    protected $fillable = ['detail', 'status', 'sender_id', 'receiver_id'];

    public function sender() {
        return $this->belongsTo(User::class, 'sender_id', 'user_id');
    }

    public function receiver() {
        return $this->belongsTo(User::class, 'receiver_id', 'user_id');
    }
}
