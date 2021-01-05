<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password','phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
    * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
    */
    public function roles()
    {
        return $this->belongsToMany('App\Role', 'admin_roles','admin_id', 'role_id');
    }

    public function subscriber()
    {
        return $this->belongsToMany('App\Subscriber', 'admin_subscribers','admin_id', 'subscriber_id');
    }

    /**
     * [getRoleId For This Admin]
     * @return [type] [description]
     */
    public function getRoleId()
    {
        return $this->roles->pluck('id')->toArray();
    }

    /**
     * [getSubscriber For This Admin]
     * @return [type] [description]
     */
    public function getSubscriberId()
    {
        return $this->subscriber->pluck('id')->toArray();
    }


}
