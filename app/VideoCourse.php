<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoCourse extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['video_id','level_id','subscriber_id','user_id'];

    /**
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function video()
    {
        return $this->belongsTo('App\Video','video_id');
    }

    public function level()
    {
        return $this->belongsTo('App\Level','level_id');
    }

}
