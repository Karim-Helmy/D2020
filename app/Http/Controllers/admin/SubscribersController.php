<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\City;
use App\User;
use App\Attachment;
use App\Video;
use App\Photo;
use Mail;
use Hash;
use App\Subscriber;
use App\Mail\UserRegistration;


class SubscribersController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function index(Request $request)
     {
         if(!userCan('subscribers')){
             return redirect('admin/index')->with([
                 'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
             ]);
         }

         $subscribers = Subscriber::with('city')->get();
         if (request()->filled('status') && $request->status == "1") {
             $subscribers = Subscriber::with('city')->where('status' ,'1')->get();
         }
         if (request()->filled('status') && $request->status == "0") {
             $subscribers = Subscriber::with('city')->where('status' ,'0')->get();
         }

         return view('admin.subscribers.index', [
             'title' => trans('admin.subscribers'),
             'index' => $subscribers,
         ]);
     }

     /**
      * Display the details resource.
      *
      * @return \Illuminate\Http\Response
      */

      public function details()
      {
          if(!userCan('subscribers')){
              return redirect('admin/index')->with([
                  'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
              ]);
          }

          $subscribers = Subscriber::get();
          $all = $subscribers->map(function ($item){
              $student     = User::where('type','3')->where('subscriber_id',$item->id)->count();
              $trainer     = User::where('type','2')->where('subscriber_id',$item->id)->count();
              $father      = User::where('type','4')->where('subscriber_id',$item->id)->count();
              $user        = User::where('subscriber_id',$item->id)->where('type','1')->first();
              (!$user) ? $user_id = -1 : $user_id = $user->id ;
              $attachment  = Attachment::where('user_id',$user_id)->count();
              $video       = Video::where('user_id',$user_id)->count();
              $photo       = Photo::where('user_id',$user_id)->count();
              return $data = [
                  'id' => $item->id,
                  'name' => $item->name,
                  'email' => $item->email,
                  'phone' => $item->phone,
                  'student' => $student,
                  'trainer' => $trainer,
                  'father' => $father,
                  'attachment' => $attachment,
                  'video' => $video,
                  'photo' => $photo,
              ];
          })->toArray();
          return view('admin.subscribers.details', [
              'title' => trans('admin.Subscribers Statistics'),
              'index' => $all,
          ]);
      }

     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
         if(!userCan('subscribers')){
             return redirect('admin/index')->with([
                 'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
             ]);
         }

         return view('admin.subscribers.create', [
             'title'    => trans("admin.add subscriber"),
             'cities' => City::all()
         ]);
     }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!userCan('subscribers')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        //Make Validation
        $this->rules['name'] = 'required|max:250';
        $this->rules['email'] = 'required|email|unique:subscribers,email';
        $this->rules['email'] = 'required|email|unique:users,username';
        $this->rules['phone'] = 'required|regex:/(05)[0-9]{8}/|size:11';
        $this->rules['address'] = 'sometimes|nullable';
        $this->rules['status'] = 'required|in:0,1';
        $this->rules['city_id'] = 'required|exists:cities,id';
        $data = $this->validate($request, $this->rules);
        //Create Subscriber

        $subscriber = Subscriber::create($data);

        //If Status == 1 Create Supervisor In Users Table
        if($subscriber->status == 1){
            $password = rand(10000,1000000);
            $user = User::where('subscriber_id',$subscriber->id)->where('type','1')->first();
            if(!$user){
                $data = User::Create(
                    [
                        'name'      => $subscriber->name,
                        'email'     => $subscriber->email,
                        'phone'     => $subscriber->phone,
                        'address'   => $subscriber->address,
                        'mobile'    => $subscriber->phone,
                        'username'  => $subscriber->email,
                        'subscriber_id'  => $subscriber->id,
                        'type'      => '1',
                        'status'    => '1',
                        'password'  => Hash::make($password),
                    ]
                );
                $data['pass'] = $password;
               // Mail::to($subscriber->email)->send(new UserRegistration($data));
            }
        }
        session()->flash('success', trans("admin.add Successfully"));
        return redirect()->route('subscribers.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!userCan('subscribers')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $subscriber = Subscriber::findOrFail($id);
        return view('admin.subscribers.edit', [
            'title' => trans("admin.edit subscriber") . ' : ' . $subscriber->name,
            'edit'  => $subscriber,
            'cities' => city::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!userCan('subscribers')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        // Make Validation
        $this->rules['name'] = 'required|max:250';
        $this->rules['email'] = 'required|email|unique:subscribers,email,'.$id;
        $this->rules['phone'] = 'required|regex:/(05)[0-9]{8}/|size:11';
        $this->rules['address'] = 'sometimes|nullable';
        $this->rules['city_id'] = 'required|exists:cities,id';
        $data = $this->validate($request, $this->rules);
        //Update Data
        $subscriber = Subscriber::where('id',$id)->update($data);
        // Success Message
        session()->flash('success', trans("admin.edit Successfully"));
        return  redirect('admin/subscribers');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPassword($id)
    {
        if(!userCan('subscribers')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $subscriber = User::where('subscriber_id',$id)->firstOrFail();
        return view('admin.subscribers.password', [
            'title' => trans("admin.edit subscriber") . ' : ' . $subscriber->name,
            'edit'  => $subscriber,
        ]);
    }

    /**
     * Update Password
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request, $id)
    {
        if(!userCan('subscribers')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        // Make Validation
        $data = $this->validate($request, [
            'username'=>'required|max:150|unique:users,username,'.$id,
            'password'=>'required|min:6|confirmed',
        ]);
        $data['password'] = Hash::make($request->password);
        //Update Data
        $subscriber = User::where('id',$id)->update($data);
        // Success Message
        session()->flash('success', trans("admin.edit Successfully"));
        return  redirect()->back();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param  bool  $redirect
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        if(!userCan('subscribers')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        if (request()->filled('id')) {
            $id = request()->id;
        }
        $subscriber = Subscriber::findOrFail($id);
        if ($subscriber) {
            $subscriber->delete();
            User::where('subscriber_id',$id)->delete();
        }
    }


    /**
     * Show the form for active or deactive the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function active($id)
    {
        if(!userCan('subscribers')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $subscriber = Subscriber::findOrFail($id);
        //Update Data
        $user = User::where('subscriber_id',$subscriber->id)->where('type','1')->first();
        if ($subscriber->status == 1) {
            $subscriber->update(['status' => '0']);
            $user->update(['status' => '0']);
        } else {
            $subscriber->update(['status' => '1']);
            $password = rand(10000,1000000);

            if(!$user){
                $data = User::Create(
                    [
                        'name'      => $subscriber->name,
                        'email'     => $subscriber->email,
                        'phone'     => $subscriber->phone,
                        'address'   => $subscriber->address,
                        'mobile'    => $subscriber->phone,
                        'username'  => $subscriber->email,
                        'subscriber_id'  => $subscriber->id,
                        'type'      => '1',
                        'status'    => '1',
                        'password'  => Hash::make($password),
                    ]
                );
                $data['pass'] = $password;
               // Mail::to($subscriber->email)->send(new UserRegistration($data));
            }else{
                $user->update(['status' => '1']);
            }
        }
        // Success Message
        session()->flash('success', trans("admin.edit Successfully"));
        return  redirect('admin/subscribers');
    }

}
