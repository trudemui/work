<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductionControl extends Model
{
    //
    protected $fillable = [
        'growing_clop_id', 'work_date', 'work_date', 'work_content_id', 'seed_id', 'seed_volume', 'equipment_id', 'equip_method_id', 'others', 'after_harvest_id'
    ];

    public function growing_crop() {
        return $this->belongsTo('App\GrowingCrop');
    }
}
