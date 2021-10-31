<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recruitment extends Model
{
    use HasFactory;

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function jobSeekers() {
        return $this->belongsToMany(JobSeeker::class);
    }

    public function employer() {
        return $this->belongsTo(Employer::class);
    }
}
