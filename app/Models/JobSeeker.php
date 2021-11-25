<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobSeeker extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'job_seeker_id';
    protected $fillable = ['birthday', 'gender', 'qualification', 'work_experience', 'education', 'skill'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function recruitments()
    {
        return $this->belongsToMany(Recruitment::class, 'job_seeker_recruitment', 'job_seeker_id', 'recruitment_id')
            ->withPivot('type', 'following', 'created_at');
    }
}
