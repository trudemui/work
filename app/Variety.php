<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Variety extends Model
{
    //
    protected $fillable = [
        'crop_id', 'variety_name'
    ];

    public function crop() {
        return $this->belongsTo('App\Crop');
    }
}
