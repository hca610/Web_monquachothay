<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobSeeker extends Model
{
    use HasFactory;
    public function indentify() {
        return $this->hasOne(User::class);
    }

    public function recruitments(){
        return $this->belongsToMany(Recruitment::class);
    }
}
