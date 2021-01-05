<?php

namespace App\Http\Controllers\trainer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\CourseUser;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($course_id)
    {
        CourseUser::where('type','2')->where('course_id',$course_id)->where('user_id',auth()->id())->firstOrFail();
        $users = CourseUser::with('user')->whereHas('user',function($query){
            $query->where('type','3');
        })->where('course_id',$course_id)->paginate(25);
        return view('trainer.users.index', [
            'users' => $users,
            'id'    => $course_id,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($course_id)
    {
        $course_users = CourseUser::where('course_id',$course_id)->get()->pluck("user_id")->toArray();
        $users = User::where('status','1')->where('subscriber_id',auth()->user()->subscriber_id)->whereIn('type', [3])
        ->whereNotIn('id',$course_users)->get();
        return view('trainer.users.create', [
            'users' => $users,
            'id'    => $course_id,
        ]);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request,$course_id)
   {
       CourseUser::where('type','2')->where('course_id',$course_id)->where('user_id',auth()->id())->firstOrFail();
       // Make Validation
       $users = User::where('status','1')->where('subscriber_id',auth()->user()->subscriber_id)->whereIn('type', [3])->get();
       $implode_name = implode(',',$users->pluck("name")->toArray());
        if($request->type == '1'){
            $this->rules['user_id'] = 'required|in:'.$implode_name;
        }else{
            $this->rules['user_id'] = 'required|array';
        }
       $this->validate($request, $this->rules);
       if($request->type == '1'){
           $check_trainer = User::where('name','like',$request->user_id)->where('subscriber_id',auth()->user()->subscriber_id)->firstOrFail();
           $user = CourseUser::updateOrCreate(
               ['course_id' => $course_id, 'user_id' => $check_trainer->id],
               ['type' =>$check_trainer->type]
           );
       }else{
           foreach ($request->user_id as $id) {
               $check = User::where('id',$id)->where('subscriber_id',auth()->user()->subscriber_id)->where('type','3')->firstOrFail();
               CourseUser::updateOrCreate(
                   ['course_id' => $course_id, 'user_id' => $id],
                   ['type' =>$check->type]
               );
           }
       }
       session()->flash('success', trans("admin.add Successfully"));
       return redirect()->back();
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function autocompleteCourse(Request $request)
   {
       $data = User::select("name")
               ->where("name","LIKE","%{$request->input('name')}%")
               ->where('type','3')
               ->where('subscriber_id',auth()->user()->subscriber_id)
               ->get();

       return response()->json($data);
   }


    /**
     * Show the form for editing the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function type($id)
    {
        $users = CourseUser::where('id',$id)->firstOrFail();
        CourseUser::where('type','2')->where('course_id',$users->course_id)->where('user_id',auth()->id())->firstOrFail();
        return view('trainer.users.type', [
            'edit' => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function typeUpdate(Request $request,$id)
    {
        $users = CourseUser::where('id',$id)->firstOrFail();
        CourseUser::where('type','2')->where('course_id',$users->course_id)->where('user_id',auth()->id())->firstOrFail();
        // Make Validation
        $this->rules['type'] = 'required|in:2,3';
        $data = $this->validate($request, $this->rules);
        //Update Data
        $users->update($data);
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
        if (request()->filled('id')) {
            $id = request()->id;
        }
        $users = CourseUser::where('id',$id)->firstOrFail();
        CourseUser::where('type','2')->where('course_id',$users->course_id)->where('user_id',auth()->id())->firstOrFail();
        $users->delete();
    }


}
