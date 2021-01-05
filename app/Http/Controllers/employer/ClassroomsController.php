<?php

namespace App\Http\Controllers\Employer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CourseUser;
use App\User;
use App\Classroom;


class ClassroomsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  int  $course_id
     * @return \Illuminate\Http\Response
     */
    public function index($course_id,$subscriber_id)
    {
        if(!userSubscriber($subscriber_id)){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        // Check Permission
        $rooms = Classroom::where('course_id',$course_id)->where('subscriber_id',$subscriber_id)->get();
        //Get Videos
        return view('employer.rooms.index', [
            'title'    => trans('admin.virtual classes'),
            'rooms' => $rooms,
            'id' => $course_id,
            'subscriber_id'    => $subscriber_id,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @param  int  $course_id
     * @return \Illuminate\Http\Response
     */
    public function create($course_id,$subscriber_id)
    {
        if(!userSubscriber($subscriber_id)){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        return view('employer.rooms.create', [
            'id'    => $course_id,
            'subscriber_id'    => $subscriber_id,
        ]);
    }

    /**
    * Store a newly created resource in storage.
    * @param  int  $course_id
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request,$course_id,$subscriber_id)
   {
       if(!userSubscriber($subscriber_id)){
           return redirect('admin/index')->with([
               'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
           ]);
       }
       //Make Validation
       $this->rules['title'] = 'required|max:200';
       $this->rules['start_date'] = 'required|date|after_or_equal:today';
       $this->rules['end_date'] = 'required|date|after:start_date';
       $this->rules['class_no'] = 'sometimes|nullable|integer';
       $this->rules['link'] = 'required|url';
       $data = $this->validate($request, $this->rules);
       //Create Video
       $data['course_id'] = $course_id;
       $data['user_id'] = User::where('type','1')->where('subscriber_id',$subscriber_id)->first()->id;
       $data['subscriber_id'] = $subscriber_id;
       if($request->start_date){
           $data['start_date'] = date('Y-m-d H:i:s',strtotime($request->start_date));
       }
       if($request->end_date){
           $data['end_date'] = date('Y-m-d H:i:s',strtotime($request->end_date));
       }
       $room = Classroom::create($data);
       session()->flash('success', trans("admin.add Successfully"));
       return redirect()->back();
   }

       /**
        * Show the form for editing the specified resource.
        *
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
           $rooms = Classroom::where('subscriber_id',$subscriber_id)->where('id',$id)->firstOrFail();
           return view('employer.rooms.edit', [
               'title' => 'edit',
               'edit'  => $rooms,
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
       public function update(Request $request, $id,$subscriber_id)
       {
           if(!userSubscriber($subscriber_id)){
               return redirect('admin/index')->with([
                   'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
               ]);
           }
           //Check Permissions
           $rooms = Classroom::where('id',$id)->firstOrFail();
           // Make Validation
           $this->rules['title'] = 'required|max:200';
           $this->rules['start_date'] = 'required|date|after_or_equal:today';
           $this->rules['end_date'] = 'required|date|after:start_date';
           $this->rules['class_no'] = 'sometimes|nullable|integer';
           $this->rules['link'] = 'required|url';
           $data = $this->validate($request, $this->rules);
           //Update Data
           $data['course_id'] = $rooms->course_id;
           $data['user_id'] = User::where('type','1')->where('subscriber_id',$subscriber_id)->first()->id;
           $data['subscriber_id'] = $subscriber_id;
           if($request->start_date){
               $data['start_date'] = date('Y-m-d H:i:s',strtotime($request->start_date));
           }
           if($request->end_date){
               $data['end_date'] = date('Y-m-d H:i:s',strtotime($request->end_date));
           }
           $rooms->update($data);
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
            //Find And Delete
            $room = Classroom::where('subscriber_id',$subscriber_id)->where('id',$id)->firstOrFail();
            $room->delete();
        }


}
