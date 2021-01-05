<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhotoCourse extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['photo_id','level_id','subscriber_id','user_id'];

    /**
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function photo()
    {
        return $this->belongsTo('App\Photo','photo_id');
    }

    public function level()
    {
        return $this->belongsTo('App\Level','level_id');
    }
}
