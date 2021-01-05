<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\User;
use Hash;

class UsersController extends Controller
{
    /**
     * [login]
     * @param  Request $request [phone,password]
     * api_url: api/father/login  [method:post]
     * @return [json]           [user data with token]
     */
    public function login(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('username', 'password');
        $validator = Validator::make(request()->all(),[
            "username"    => "required",
            "password" => "required",
        ]);
        if($validator->fails()){
            return sendError(implode(',',$validator->errors()->all()));
        }
            // attempt to verify the credentials and create a token for the user
            $token = User::where('username',request()->username)->where('type','3')->first();
            if (!$token) {
                return sendError(trans('login.invalid_username'));
            }
            if (!Hash::check($request->get('password'), $token->password)) {
              return sendError(trans('login.invalid_password'));
            }
            $user = User::where('username',request()->username)->where('type','3')->first();
            // all good so return the token
            return sendResponse(trans('admin.login success'),$user);

    }

    public function show(Request $request)
    {
        $curl = curl_init();
        $auth_data = array(
            'username' 		=> 'student',
            'password' 	    => '123456',
        );
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $auth_data);
        curl_setopt($curl, CURLOPT_URL, 'http://localhost/besteam/besteam/api/site/login');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 100);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        $result = curl_exec($curl);
        if(!$result){die("Connection Failure");}
        curl_close($curl);
        $decode = json_decode($result,true);
        if($decode['status']){
            //return $decode['data']['id'];
            auth()->loginUsingId($decode['data']['id']);
        }else{
            return $decode['message'];
        }


    }


    public function profile(Request $request)
   {
       $user = User::where('id',request()->id)->first(['id','name','username']);
       if (!$user) {
           return sendError(trans('login.invalid_username'));
       }
       return sendResponse(trans('admin.login success'),$user);
   }


}
