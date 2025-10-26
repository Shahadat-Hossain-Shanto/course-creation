<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'type',
        'title',
        'body',
        'file',
        'position',
    ];

    public function module()
    {
        return $this->belongsTo(Module::class, 'module_id');
    }
}
