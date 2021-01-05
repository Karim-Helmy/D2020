<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttachmentCourse extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['attachment_id','level_id','subscriber_id','user_id'];

    /**
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function attachment()
    {
        return $this->belongsTo('App\Attachment','attachment_id');
    }

    public function level()
    {
        return $this->belongsTo('App\Level','level_id');
    }

}
