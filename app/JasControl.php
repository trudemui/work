<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JasControl extends Model
{
    //
    protected $fillable = [
        'growing_clop_id', 'work_date', 'packaging_unit', 'consignments_count', 'weight', 'destination_id', 'used_count'
    ];

    public function growing_crop() {
        return $this->belongsTo('App\GrowingCrop');
    }
}
