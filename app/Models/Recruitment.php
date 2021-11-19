<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recruitment extends Model
{
    use HasFactory;

    protected $primaryKey = 'recruitment_id';
    protected $fillable = ['category_id', 'min_salary', 'job_name', 'detail', 'status', 'requirement', 'address'];

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function jobSeekers() {
        return $this->belongsToMany(JobSeeker::class)->withPivot('type', 'created_at');
    }

    public function employer() {
        return $this->belongsTo(Employer::class, 'employer_id');
    }
}
