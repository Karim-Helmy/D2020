<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $fillable = [
        'name',
    ];

    public function images()
    {
        return $this->hasMany('App\StageImages');
    }
    public function Cat()
    {
        return $this->belongsTo('App\Category','category_id');
    }
}
