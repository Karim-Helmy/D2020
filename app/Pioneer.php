<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pioneer extends Model
{
  protected $fillable = ["link","category_id"];

  /**
  * @return \Illuminate\Database\Eloquent\Relations\belongsTo
  */
    public function category()
    {
        return $this->belongsTo('App\Category','category_id');
    }

}
