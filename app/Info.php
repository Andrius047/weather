<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    public $timestamps = false;

    protected $fillable = [
        "city_id", "temp", "deg", "speed"
    ];
}
