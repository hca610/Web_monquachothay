<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recruitment extends Model
{
    use HasFactory;

    protected $primaryKey = 'recruitment_id';
    protected $fillable = ['category', 'min_salary', 'job_name', 'detail', 'status', 'requirement', 'address'];
    protected $hidden = ['employer'];

    public function jobSeekers()
    {
        return $this->belongsToMany(JobSeeker::class, 'job_seeker_recruitment', 'recruitment_id', 'job_seeker_id')
            ->withPivot('type', 'following', 'created_at');
    }

    public function employer()
    {
        return $this->belongsTo(Employer::class, 'employer_id', 'employer_id');
    }
}
