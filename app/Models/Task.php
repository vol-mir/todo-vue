<?php

namespace App\Models;

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

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function isAuthor($checkUser)
    {
        if($this->user->getId() === $checkUser->getId()) {
            return true;
        }
        return false;
    }
}
