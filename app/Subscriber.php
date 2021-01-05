<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Subscriber extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','email','phone','email','address','logo','title','description','photos','city_id','status','payment'];

    /**
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function city()
    {
        return $this->belongsTo('App\City','city_id');
    }

}
