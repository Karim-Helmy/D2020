<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AboutPage extends Model
{
  protected $fillable = ["vision_ar","vision_en","mission_ar","mission_en","vision_photo","mission_photo"];
}
