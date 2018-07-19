<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class city extends Model
{
    public $timestamps = false;

    protected $fillable = [
        "city", "public_id"
    ];
}
