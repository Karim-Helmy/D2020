<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
  protected $fillable = ["title","description","start_date","file_upload","end_date","total","file","subscriber_id","level_id","user_id"];

  /**
  * @return \Illuminate\Database\Eloquent\Relations\HasMany
  */

  public function projectUser()
  {
      return $this->hasMany('App\ProjectUser');
  }

  /**
  * @return \Illuminate\Database\Eloquent\Relations\belongsTo
  */
  public function user()
  {
      return $this->belongsTo('App\User','user_id');
  }

  public function level()
  {
      return $this->belongsTo('App\Level','level_id');
  }



}
