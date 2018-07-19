<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class emails extends Model
{
    public $timestamps = false;

    protected $fillable = [
        "email"
    ];
}
