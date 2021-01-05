<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
  protected $fillable = ["title","description","attachments","admin_id","user_id","category_id","stage_id"];

  /**
  * @return \Illuminate\Database\Eloquent\Relations\belongsTo
  */

    public function category()
    {
        return $this->belongsTo('App\Category','category_id');
    }

    public function user()
    {
              return $this->belongsTo('App\User','user_id');
    }

    public function stage()
    {
        return $this->belongsTo('App\Stage','stage_id');
    }

}
