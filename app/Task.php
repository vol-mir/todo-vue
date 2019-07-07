<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['name', 'done'];

    protected $casts = [
        'done' => 'boolean',
    ];

    protected $attributes = [
        'done' => false
    ];
}
