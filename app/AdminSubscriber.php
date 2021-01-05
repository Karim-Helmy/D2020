<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminSubscriber extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['admin_id','subscriber_id'];

    /**
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function admin()
    {
        return $this->belongsTo('App\Admin','admin_id');
    }

    public function subscriber()
    {
        return $this->belongsTo('App\Subscriber','subscriber_id');
    }
}
