<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectUser extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['project_id','user_id','answer','answer_file','grade','notes'];

    /**
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function project()
    {
        return $this->belongsTo('App\Project','project_id');
    }

}
