<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
  protected $fillable = ["title","ordering","subscriber_id","course_id"];

  /**
  * @return \Illuminate\Database\Eloquent\Relations\belongsTo
  */
  public function course()
  {
      return $this->belongsTo('App\Course','course_id');
  }
}
