<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
    ];

    public function images()
    {
        return $this->hasMany('App\ProductImages');
    }
    public function Cat()
    {
        return $this->belongsTo('App\Stage','stage_id');
    }
}
