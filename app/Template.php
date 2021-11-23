<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    
    protected $fillable = [
        'category_id', 'item_number', 'item_name', 'body'
    ];

    public function categories() {
        return $this->belongsToMany('App\Category');
    }
}
