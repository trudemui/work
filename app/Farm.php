<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    //
    protected $fillable = [
        'farm_number', 'farm_area', 'farm_address', 'cultivation_area'
    ];
}
