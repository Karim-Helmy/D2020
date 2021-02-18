<?php

namespace App\Http\Controllers\api\student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Father\User;
use JWTAuth;
use Hash;

class UsersController extends Controller
{
    /**
     * [login]
     * @param  Request $request [username,password]
     * api_url: api/student/login  [method:post]
     * @return [json]           [user data with token]
     */
    public function login(Request $request)
    {
        // Validation Mobile
        $validator = Validator::make(request()->all(),[
            "username"    => "required",
            "password"    => "required",
        ]);
        if($validator->fails()){
            return sendError(implode(',',$validator->errors()->all()));
        }
        // attempt to verify the credentials and create a token for the user
        $user = User::where('username',request()->username)->where('type','3')->first(['id','name','username','password','email','mobile','status','address','birth_date','photo']);
        if (!$user) {
            return sendError(trans('login.invalid_username'));
        }
        if (!Hash::check($request->get('password'), $user->password)) {
          return sendError(trans('login.invalid_password'));
        }
        $user->setAppends([]);
        $user['token'] = JWTAuth::fromUser($user);
        $user['type'] = 'user';

        $user['profile_link'] = route('api.student.profile');
        // all good so return the token
        return sendResponse(trans('admin.login success'),$user);

    }

    /**
     * [Check Token]
     * @return [array] [token]
     * api_url: api/student/check  [method:get]
     */
    public function check()
    {
        return sendResponse('Token Is Valid','');
    }

    /**
     * [profile]
     * @return [array] [Student Data]
     * api_url: api/student/profile  [method:get]
     */
    public function profile()
    {
        $user = User::where('id',auth()->id())->where('type','3')->first(['id','name','username','email','mobile','status','address','birth_date','photo']);
        if(!$user){
            return sendError(trans('login.Please Check Your Data'));
        }
        $user->setAppends([]);
        $user['type'] = 'user';

        $user['update_link'] = route('api.student.profile.update');
        return sendResponse(trans('login.profile'),$user);
    }


    /**
     * [Update Profrile]
     * @param Request $request [update (mobile -  password)]
     * api_url: api/student/profile/update  [method:post]
     */
    public function update(Request $request)
    {
           // Get User Data
           $user = User::where('id',auth()->id())->first(['id','name','username','email','mobile','address','birth_date','photo']);
           //Make Validation
           $validator = Validator::make(request()->all(), [
               "username"     => 'sometimes|nullable|unique:users,username,'.$user->id,
               "name"       => "sometimes|nullable|max:200",
               "address"       => "sometimes|nullable|max:200",
               "email"      => 'sometimes|nullable|email',
               "image"      => 'sometimes|nullable|image',
               "id_number"      => 'sometimes|nullable|min:10|max:17',
               "mobile"      => 'sometimes|nullable|numeric|unique:users,mobile,'.$user->id,
               "birth_date"      => 'sometimes|nullable|date|before:2015-01-01',
               "password"      => 'sometimes|nullable|min:6',
           ]);
           //If Validation Errors
           if ($validator->fails()) {
               return sendError(implode(',',$validator->errors()->all()));
           }
           //IF Password Update Make Hash To New Password
           if($request->password){
               $request['password'] = Hash::make($request->password);
           }
           //IF Birth_date Update Make Convert Format
           if($request->birth_date){
               $request['birth_date'] = date('Y-m-d',strtotime($request->birth_date));
           }
           // Upload Image
           if ($request->hasFile('image')) {
              $destination = "uploads/" . $user->subscriber_id . "/profile/" . date("Y") . "/" . date("m") . "/";
              $request['photo'] = UploadImages($destination, $request->file('image')); // Upload Image
          }
           //Update And Success Message
           $user->update(request()->all());
           return sendResponse(trans('admin.edit Successfully'),$user->setAppends([]));
    }

    public function register(Request $request)
    {

        $validator = Validator::make(request()->all(), [
            "username"     => 'required|unique:users,username',
            "name"       => "required|max:200",
            "address"       => "sometimes|nullable|max:200",
            "email"      => 'required|nullable|email',
            "id_number"      => 'sometimes|nullable|min:10|max:17',
            "mobile"      => 'required|nullable|numeric|unique:users,mobile',
            "password"      => 'required|nullable|min:6',
        ]);
        //If Validation Errors
        if ($validator->fails()) {
            return sendError(implode(',',$validator->errors()->all()));
        }
        //Create User And Success Message
        $user =new User();
        // Upload Image
        if ($request->hasFile('image')) {
            $destination = "uploads/" . $user->subscriber_id . "/profile/" . date("Y") . "/" . date("m") . "/";
            $request['photo'] = UploadImages($destination, $request->file('image')); // Upload Image
        }
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->id_number = $request->id_number;
        $user->mobile = $request->mobile;
        $user->address = $request->address;
        $user->type = '3';
        $user->password = Hash::make($request->password);
        $user->save();

        return sendResponse(trans('admin.add Successfully'),$user->setAppends([]));
    }



}
