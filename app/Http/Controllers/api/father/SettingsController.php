<?php

namespace App\Http\Controllers\api\father;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{

    /**
 * [Contact Us Page]
 * api_url: api/father/contact  [method:get]
 * @return [json]
 */
public function contact()
{
    $setting = [
        'phone'        => GetSetting('phone'),
        'whatsapp'     => '966'.ltrim(GetSetting('whatsapp'), '0'),
        'email'        => GetSetting('email'),
        'site_url'     => 'https://be-steam.com/',
        'address'      => GetSetting('address'),
        'twitter_url'  => GetSetting('twitter'),
        'facebook_url' => GetSetting('facebook'),
        'youtube_url'  => GetSetting('youtube'),
        'instgram_url' => GetSetting('inst'),
    ];
    return sendResponse(trans('admin.contact_us'),$setting);
}

/**
 * [About Us Page]
 * api_url: api/father/about  [method:get]
 * @return [json]
 */
public function about()
{
    if(request()->header('lang') == "ar"){
        $setting = [
            'content'  => GetSetting('about_us_ar'),
            'site_url' => 'https://be-steam.com/',
        ];
    }else{
        $setting = [
            'content'  => GetSetting('about_us_en'),
            'site_url' => 'https://be-steam.com/',
        ];
    }
    return sendResponse(trans('admin.about_us'),$setting);
}

}
