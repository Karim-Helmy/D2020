<?php

namespace App\Http\Controllers\Employer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Course;
use App\CourseUser;
use App\StudentGroup;
use App\GroupUser;

class GroupUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($course_id,$id)
    {
        if(!userSubscriber($id)){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        $groups = StudentGroup::where('subscriber_id',$id)->where('course_id',$course_id)->get();
        return view('employer.groups.index', [
            'groups' => $groups,
            'id'    => $course_id,
            'title'    => trans('admin.group courses'),
            'subscriber_id'    => $id,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($course_id,$id)
    {
        if(!userSubscriber($id)){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        $users = User::where('subscriber_id',$id)->where('status','1')->where('type','3')->get();
        return view('employer.groups.create', [
            'users' => $users,
            'id'    => $course_id,
            'subscriber_id'    => $id,
        ]);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request,$course_id,$id)
   {
       if(!userSubscriber($id)){
           return redirect('admin/index')->with([
               'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
           ]);
       }
       Course::where('id',$course_id)->where('subscriber_id',$id)->firstOrFail();
       // Make Validation
       $users = User::where('status','1')->where('subscriber_id',$id)->where('type', '3')->get();
       $implode = implode(',',$users->pluck("id")->toArray());
       $this->rules['title'] = 'required|max:200';
       $this->rules['user_id'] = 'required|array|in:'.$implode;
       $data = $this->validate($request, $this->rules);
       //Create User And Success Message
       $group = StudentGroup::create([
           'title' => $request->title,
           'subscriber_id' => $id,
           'course_id' => $course_id,
       ]);
       foreach ($request->user_id as $key => $user) {
           GroupUser::updateOrCreate(
               ['student_group_id' => $group->id, 'user_id' => $user]
           );
           CourseUser::updateOrCreate(
               ['course_id' => $course_id, 'user_id' => $user],
               ['type' =>'3','group_id' => $group->id]
           );
       }
       session()->flash('success', trans("admin.add Successfully"));
       return redirect()->back();
   }


    /**
     * Show the form for editing the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,$subscriber_id)
    {
        if(!userSubscriber($subscriber_id)){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        $users = User::where('subscriber_id',$subscriber_id)->where('status','1')->where('type','3')->get();
        $groups= StudentGroup::with('groupUser')->where('id',$id)->where('subscriber_id',$subscriber_id)->firstOrFail();
        $choose = [];
        foreach ($groups->groupUser as $user) {
            $choose[] = $user->user_id;
        }
        return view('employer.groups.edit', [
            'edit' => $groups,
            'users' => $users,
            'choose' => $choose,
            'id'    => $id,
            'subscriber_id'    => $subscriber_id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id,$subscriber_id)
    {
        if(!userSubscriber($subscriber_id)){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        $groups = StudentGroup::where('id',$id)->where('subscriber_id',$subscriber_id)->firstOrFail();
        // Make Validation
        $users = User::where('status','1')->where('subscriber_id',$subscriber_id)->where('type', '3')->get();
        $implode = implode(',',$users->pluck("id")->toArray());
        $this->rules['title'] = 'required|max:200';
        $this->rules['user_id'] = 'required|array|in:'.$implode;
        $data = $this->validate($request, $this->rules);
        //Update Data
        $groups->update([
            'title' => $request->title,
            'subscriber_id' => $subscriber_id,
        ]);
        GroupUser::where('student_group_id',$id)->delete();
        CourseUser::where('group_id',$id)->delete();
        foreach ($request->user_id as $key => $user) {
            GroupUser::updateOrCreate(
                ['student_group_id' => $id, 'user_id' => $user]
            );
            CourseUser::updateOrCreate(
                ['course_id' => $groups->course_id, 'user_id' => $user],
                ['type' =>'3','group_id' => $id]
            );
        }
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
    public function destroy($subscriber_id)
    {
        if(!userSubscriber($subscriber_id)){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        if (request()->filled('id')) {
            $id = request()->id;
        }
        $groups = StudentGroup::where('id',$id)->where('subscriber_id',$subscriber_id)->firstOrFail();
        $groups->delete();
    }

}
