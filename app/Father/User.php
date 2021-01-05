<?php
namespace App\Father;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'mobile', 'phone', 'address', 'nationality', 'birth_date', 'status', 'type', 'password','subscriber_id','photo','father_id','id_number'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * [protected append "count of courses"]
     * @var [type]
     */
    protected $appends = ['activity_count'];

    /**
    * @return \Illuminate\Database\Eloquent\Relations\belongsTo
    */
    public function subscriber()
    {
        return $this->belongsTo('App\Subscriber','subscriber_id');
    }

    /**
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function son()
    {
        return $this->hasMany(User::class,'father_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function course()
   {
       return $this->belongsToMany('App\Course', 'course_users','user_id', 'course_id');
   }


    /**
   * Get the identifier that will be stored in the subject claim of the JWT.
   *
   * @return mixed
   */
  public function getJWTIdentifier()
  {
      return $this->getKey();
  }

  /**
   * Return a key value array, containing any custom claims to be added to the JWT.
   *
   * @return array
   */
  public function getJWTCustomClaims()
  {
      return [];
  }

  /**
    * [getActivityCountAttribute To Count Courses Of Sons]
    * @return [int] [Count Of Courses]
    */
    public function getActivityCountAttribute()
   {
       return $this->course()->count();
   }

  /**
    * [Update In Formate Date]
    * @return [date] [Change Format Last Login]
    */
   public function getLastLoginAttribute()
    {
        if(!empty($this->attributes['last_login'])){
            return date('d M',strtotime($this->attributes['last_login']));
        }
    }

    /**
     * [Add Full Path To Photo]
     * @return [date] [Full Path]
     */
    public function getPhotoAttribute()
     {
         if(!empty($this->attributes['photo'])){
             return asset('uploads/'.$this->attributes['photo']);
         }
     }

     /**
      * [Convert Type To String]
      * @return [date] [Type]
      */
     public function getTypeAttribute()
      {
          if($this->attributes['type'] == '1'){
              return trans('admin.supervisor');
          }elseif ($this->attributes['type'] == '2') {
              return trans('admin.trainer');
          }elseif ($this->attributes['type'] == '3') {
              return trans('admin.student');
          }elseif ($this->attributes['type'] == '4') {
              return trans('admin.father');
          }else{
              return "Super Admin";
          }
      }


}
