<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GrowingCrop extends Model
{
    //
    protected $fillable = [
        'user_id', 'variety_id', 'starting_date', 'farm_id', 'del_flg'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function variety() {
        return $this->belongsTo('App\Variety');
    }

    public function farm() {
        return $this->belongsTo('App\Farm');
    }

    public function production_controls() {
        return $this->hasMany('App\ProductionControl');
    }
}
