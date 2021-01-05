<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin;
use Hash;
use App\City;
use App\Subscriber;
use App\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{

    /**
     * [index Admin]
     * @return [type] [description]
     */
    public function index()
    {
        $today = date('Y-m-d');
        // For Statistics
        $subsciber_all = Subscriber::count();
        $subsciber_agree = Subscriber::where('status','1')->count();
        $subsciber_waiting = Subscriber::where('status','0')->count();
        $subsciber_today = Subscriber::whereDate('created_at',$today)->count();
        // For Chart
        $cities = DB::table('subscribers')
                     ->select(DB::raw('count(city_id) as city_count,city_id, cities.name'))
                     ->rightJoin('cities', 'subscribers.city_id', '=', 'cities.id')
                     ->groupBy('city_id','cities.name')
                     ->get();
        return view('admin.index', [
            // For Statistics
            'subsciber_all' => $subsciber_all,
            'subsciber_agree' => $subsciber_agree,
            'subsciber_waiting' => $subsciber_waiting,
            'subsciber_today' => $subsciber_today,
            // For Chart
            'cities' => $cities
        ]);
    }


    /**
     * [login Admin]
     * @return [type] [description]
     */
    public function login(){
        if(auth()->guard('webAdmin')->check()){
            return redirect('/admin/index');
        }
        return view('admin.login');
    }

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        //Validation
        $data = $this->validate($request, [
        'email'=>'required|email',
        'password'=>'required|min:6',
        ]);
        //IF Check On Remember Me
        if($request->remember == "on"){
            $remember = true;
        }else{
            $remember = false;
        }
        //Succes Message
        if(auth()->guard('webAdmin')->attempt($data,$remember)){
            return redirect('admin/index')->with([
            'message' => trans('admin.login success'),
            ]);
        }
        //Error  Message
        return redirect('/admin/login')->with([
        'error' => trans('admin.login fail'),
        ]);
    }

    /**
     * [logout description]
     * @return [type] [description]
     */
    public function logout()
    {
        auth()->guard('webAdmin')->logout();
        return redirect('/admin/login');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!userCan('admin')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $admin = Admin::where('id',$id)->with('roles','subscriber')->firstOrFail();
        return view('admin.admins.edit', [
            'title' => trans("admin.edit admins") . ' : ' . $admin->username,
            'edit'  => $admin,
            'roles'=>Role::all(),
            'subscribers'=>Subscriber::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        if(!userCan('admin')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $admin = Admin::where('id',$id)->firstOrFail();
        //Validation
        $this->rules['email'] = 'required|unique:admins,email,'.$id;
        $this->rules['username'] = 'required';
        $this->rules['phone'] = 'required|numeric|unique:admins,phone,'.$id;
        $this->rules['role_id'] = 'sometimes|nullable|exists:roles,id';
        $this->rules['subscriber_id'] = 'sometimes|nullable|exists:subscribers,id';

        $data = $this->validate($request, $this->rules);
        //Update Admin
        $user = Admin::find($id);
        $user->username = $request->username;
        $user->email = $request->email;
        $user->phone = $request->phone;
        //Make Hash To Password
        if($request->password){
            $user->password = Hash::make($request->password);
        }
        $user->save();
        if($request->role_id){
            $user->roles()->sync($data['role_id']);
        }
        if($request->subscriber_id){
            $user->subscriber()->sync($data['subscriber_id']);
        }
        // Success Message
        session()->flash('success', trans("admin.edit Successfully"));
        return  redirect('admin/admins');
    }

    /**
     * [create New Admin]
     * @return [Create Page]
     */
    public function create(){
        if(!userCan('admin')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $subscribers = Subscriber::where('status','1')->get();
        $roles = Role::orderBy('id','desc')->get();
        return view('admin.admins.create',[
            'title'=>trans('admin.create new admin'),
            'subscribers' => $subscribers,
            'roles' => $roles,
        ]);
    }

    /**
     * [save new admin]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function save(Request $request){
        if(!userCan('admin')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $data = $this->validate($request, [
            'username'=>'required',
            'email'=>'required|min:6|email|unique:admins,email',
            'phone'=>'required|numeric|unique:admins,phone',
            //'phone'=>'required|numeric|regex:/(05)[0-9]{8}/|unique:admins,phone',
            'password'=>'required|min:6',
            'role_id' => 'sometimes|nullable|exists:roles,id',
            'subscriber_id' => 'sometimes|nullable|exists:subscribers,id'
        ]);
        $data['password'] = Hash::make($request->password);
        $admin = Admin::Create($data);
        if($request->role_id){
            $admin->roles()->attach($data['role_id']);
        }
        if($request->subscriber_id){
            $admin->subscriber()->attach($data['subscriber_id']);
        }

        return redirect(aurl('/admins'))->with(['success' => "Created Successfully"]);
    }

    /**
     * [admins description]
     * @return [type] [description]
     */
    public function admins(){
        if(!userCan('admin')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        return view('admin.admins.index',[
            'title'=>trans('admin.admins'),
            'admins'=>Admin::orderBy('created_at','desc')->get()
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function user()
    {
        $admin = Admin::where('id',auth()->guard('webAdmin')->id())->firstOrFail();
        return view('admin.admins.user', [
            'title' =>  ' تعديل بيانات : ' . $admin->username,
            'edit'  => $admin,
        ]);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function updateUser(Request $request)
   {
       $id = auth()->guard('webAdmin')->id();
       $this->rules['email'] = 'required|unique:admins,email,'.$id;
       $this->rules['username'] = 'required';
       $this->rules['phone'] = 'required|numeric|unique:admins,phone,'.$id;
       $data = $this->validate($request, $this->rules);

       $user = Admin::find($id);
       $user->username = $request->username;
       $user->email = $request->email;

       if($request->password){
           $user->password = Hash::make($request->password);
       }
       $user->save();
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
        if(!userCan('admin')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        if (request()->filled('id')) {
            $id = request()->id;
        }
        $user = Admin::findOrFail($id);
        if ($user) {
            $count = Admin::count();
            if($count > 1){
                $user->delete();
            }
        }
    }

}
