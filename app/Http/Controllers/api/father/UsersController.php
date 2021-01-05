<?php

namespace App\Http\Controllers\api\father;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Father\User;
use JWTAuth;
use Hash;
use App\Subscriber;
use App\PackageOption;

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
        // Validation Mobile
        $validator = Validator::make(request()->all(),[
            "mobile"    => "required",
        ]);
        if($validator->fails()){
            return sendError(implode(',',$validator->errors()->all()));
        }
        // attempt to verify the credentials and create a token for the user
        $user = User::where('mobile',request()->mobile)->where('type','4')->first(['id','name','mobile','photo','subscriber_id']);
        if (!$user) {
            return sendError(trans('login.invalid_mobile'));
        }
        $package_id = Subscriber::where('id',$user->subscriber_id)->first()->package_id;
        if($package_id){
            $package = PackageOption::where('package_id',$package_id)->where('option_id','2')->first()->value;
            if($package != 'مفعل'){
                return sendError(trans('admin.member_inactive'));
            }
        }
        $user->setAppends([]);
        $user['token'] = JWTAuth::fromUser($user);
        // all good so return the token
        return sendResponse(trans('admin.login success'),$user);

    }

    /**
     * [Check Token]
     * @return [array] [token]
     * api_url: api/father/check  [method:get]
     */
    public function check()
    {
        return sendResponse('Token Is Valid','');
    }

    /**
     * [profile]
     * @return [array] [Father Data]
     * api_url: api/father/profile  [method:get]
     */
    public function profile()
    {
        $user = User::with('son:id,name,last_login,father_id,photo')->where('id',auth()->id())->first(['id','name','mobile','photo']);
        if(!$user){
            return sendError(trans('login.Please Check Your Data'));
        }
        $user->setAppends([]);
        $user['supervisor_id'] = $this->supervisor();
        return sendResponse(trans('login.profile'),$user);
    }


    /**
     * [Update Profrile]
     * @param Request $request [update (mobile -  password)]
     * api_url: api/father/profile/update  [method:post]
     */
    public function update(Request $request)
    {
           // Get User Data
           $user = User::where('id',auth()->id())->first(['id','name','mobile','photo']);
           //Make Validation
           $validator = Validator::make(request()->all(), [
               "mobile"     => "sometimes|nullable|regex:/(05)[0-9]{8}/|size:10|unique:users,mobile,".$user->id,
               "name"       => "sometimes|nullable|max:200",
               "image"      => 'sometimes|nullable|image',
           ]);
           //If Validation Errors
           if ($validator->fails()) {
               return sendError(implode(',',$validator->errors()->all()));
           }
           //IF Password Update Make Hash To New Password
           if($request->password){
               $request['password'] = Hash::make($request->password);
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

   /**
    * Refresh a token.
    *
    * @return \Illuminate\Http\JsonResponse
    */
   public function supervisor()
   {
       return User::where('subscriber_id',auth()->user()->subscriber_id)->where('type','1')->first()->id ?? '';
   }




}
