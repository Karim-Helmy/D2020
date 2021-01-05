<?php
namespace App\Father;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
  protected $fillable = ["sender_id","receiver_id","subject","message","views"];

  /**
  * @return \Illuminate\Database\Eloquent\Relations\belongsTo
  */
  public function user()
  {
      return $this->belongsTo('App\Father\User','sender_id');
  }
}
