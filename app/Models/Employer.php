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

    public function indentify()
    {
        return $this->hasOne(User::class);
    }

    public function recruitments()
    {
        return $this->hasMany(Recruitment::class);
    }
}
