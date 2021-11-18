<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobSeeker extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'job_seeker_id';
    protected $fillable= ['birthday', 'gender', 'qualification', 'work_experience', 'education', 'skill'];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function recruitments()
    {
        return $this->belongsToMany(Recruitment::class)->withPivot('type', 'created_at');
    }
}
