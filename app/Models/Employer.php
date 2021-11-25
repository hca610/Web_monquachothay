<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'employer_id';
    protected $fillable = ['about_us', 'image_link', 'num_employee', 'category_id'];

    public function user()
    {
        return $this->hasOne(User::class, 'user_id');
    }

    public function recruitments()
    {
        return $this->hasMany(Recruitment::class, 'employer_id', 'employer_id');
    }
}
