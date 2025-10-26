<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'feature_video',
    ];

    public function modules()
    {
        return $this->hasMany(Module::class, 'course_id');
    }
}
