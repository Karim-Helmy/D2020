<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
    ];

    /**
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function course()
    {
        return $this->hasMany('App\Course');
    }

    public function pioneer()
    {
        return $this->hasMany('App\Pioneer');
    }

}
