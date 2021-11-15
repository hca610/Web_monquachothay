<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobSeeker extends Model
{
    use HasFactory;

    protected $fillable= ['birthday', 'gender', 'qualification', 'work_experience', 'education', 'skill'];
    public $timestamps = false;

    protected $primaryKey = 'job_seeker_id';

    public function indentify()
    {
        return $this->hasOne(User::class);
    }

    public function recruitments()
    {
        return $this->belongsToMany(Recruitment::class)->withPivot('type', 'created_at');
    }
}
