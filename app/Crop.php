<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Crop extends Model
{
    //
    protected $fillable = [
        'crop_name'
    ];

    public function varieties() {
        return $this->hasMany('App\Variety');
    }
}
