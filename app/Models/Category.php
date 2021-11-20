<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'category_id';
    protected $fillable = ['name'];

    public function recruitments() {
        return $this->hasMany(Recruitment::class, 'category_id');
    }
}
