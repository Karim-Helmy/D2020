<?php

namespace App\Http\Controllers\Employer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CourseUser;
use App\GroupUser;
use App\StudentGroup;
use App\ProjectUser;
use App\Project;
use App\User;
use App\Level;

class ProjectsController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $level_id
     * @return \Illuminate\Http\Response
     */
    public function index($level_id,$subscriber_id)
    {
        if(!userSubscriber($subscriber_id)){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        $projects = Project::where('subscriber_id',$subscriber_id)->where('level_id',$level_id)->with('level.course:id,title')->get();
        $course_id = Level::where('id',$level_id)->firstOrFail()->course_id;
        return view('employer.projects.index', [
            'title' => trans('admin.projects'),
            'projects' => $projects,
            'id' => $level_id,
            'course_id' => $course_id,
            'subscriber_id'    => $subscriber_id,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @param  int  $level_id
     * @return \Illuminate\Http\Response
     */
    public function create($level_id,$subscriber_id)
    {
        if(!userSubscriber($subscriber_id)){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        $level = Level::where('id',$level_id)->first();
        $users = CourseUser::where('course_id',$level->course_id)->with('user')->where('type','3')->get();
        $groups = StudentGroup::where('subscriber_id',$subscriber_id)->where('course_id',$level->course_id)->get();
        return view('employer.projects.create', [
            'id'     => $level_id,
            'course_id'     => $level->course_id,
            'users'  => $users,
            'groups' => $groups,
            'subscriber_id'    => $subscriber_id,
        ]);
    }

    /**
    * Store a newly created resource in storage.
    * @param  int  $level_id
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request,$level_id,$subscriber_id)
   {
       if(!userSubscriber($subscriber_id)){
           return redirect('admin/index')->with([
               'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
           ]);
       }
       //Make Validation
       $this->rules['title'] = 'required|max:200';
       $this->rules['description'] = 'sometimes|nullable';
       $this->rules['start_date'] = 'required|date|after_or_equal:today';
       $this->rules['end_date'] = 'required|date|after:start_date';
       $this->rules['total'] = 'required|integer';
       $this->rules['image'] = 'sometimes|nullable|mimes:doc,csv,xlsx,xls,docx,ppt,odt,ods,odp,pdf,zip,png,jpg,gif,jpeg';
       $this->rules['file_upload'] = 'required|in:0,1';
       $this->rules['user_id'] = 'sometimes|nullable|array|in:'.implode(',',User::where('type','3')->where('subscriber_id',$subscriber_id)->get()->pluck('id')->toArray());
       $this->rules['group_id'] = 'sometimes|nullable|array|exists:student_groups,id';
       $data = $this->validate($request, $this->rules);
       //Create Project
       $level = Level::where('id',$level_id)->where('subscriber_id',$subscriber_id)->firstOrFail(); //For Check
       // Fixed Data
       $data['user_id'] = User::where('type','1')->where('subscriber_id',$subscriber_id)->first()->id;
       $data['level_id'] = $level_id;
       if($request->start_date){
           $data['start_date'] = date('Y-m-d H:i:s',strtotime($request->start_date));
       }
       if($request->end_date){
           $data['end_date'] = date('Y-m-d H:i:s',strtotime($request->end_date));
       }
       $data['subscriber_id'] = $subscriber_id;
       // Start Upload File
       if ($request->hasFile('image')) {
           $destination = public_path('uploads/uploads/'). $subscriber_id . "/projects/" . date("Y") . "/" . date("m") . "/";
           $destination_database = "uploads/" . $subscriber_id . "/projects/" . date("Y") . "/" . date("m") . "/";
           //Setting For Name file and Path
           $file = request()->file('image');
           $name = $file->getClientOriginalName(); // get image name
           $extension = $file->getClientOriginalExtension(); // get image extension
           $sha1 = sha1($name); // hash the image name
           $random = rand(1, 1000000); // Random To Name
           $name_database = $random . "_" . date("y-m-d-h-i-s") . "_" . $sha1; // To use it without extension
           $fileName = $random . "_" . date("y-m-d-h-i-s") . "_" . $sha1 . "." . $extension; // create new name for the file
           $file->move($destination, $fileName); // Upload Attachment
           $data['file'] = $destination_database . $fileName; // Create Name To Send It
       }
       // End Upload File
       $project = Project::create($data);

       //If select students
       if($request->type == '1'){
       //Assign Users To Project
       if(!empty($request->group_id)){
           foreach ($request->group_id as $key => $group) {
               $user_group = GroupUser::where('student_group_id',$group)->get();
               foreach ($user_group as $key => $user) {
                   ProjectUser::UpdateOrCreate(
                       ['project_id'  => $project->id,
                        'user_id'     => $user->user_id]
                  );
               }
           }
       }
       if(!empty($request->user_id)){
           foreach ($request->user_id as $key => $user) {
               ProjectUser::UpdateOrCreate(
                   ['project_id'  => $project->id,
                    'user_id'     => $user]
              );
           }
       }
       //IF Assign To ALl Students
       }else{
           $users = CourseUser::where('course_id',$level->course_id)->with('user')->where('type','3')->get();
           if(!empty($users)){
               foreach ($users as $key => $user) {
                   ProjectUser::UpdateOrCreate(
                       ['project_id'  => $project->id,
                       'user_id'     => $user->user_id]
                   );
               }
           }
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
        $project = Project::where('id',$id)->with('projectUser:project_id,user_id')->firstOrFail();
        $choose = [];
        $choose_users = $project->projectUser;
        foreach ($project->projectUser as $user) {
            $choose[] = $user->user_id;
        }
        //$users  = User::where('subscriber_id',$subscriber_id)->where('type','3')->get();
        $level = Level::where('id',$project->level_id)->first();
        $users = CourseUser::where('course_id',$level->course_id)->with('user')->where('type','3')->get();
        $groups = StudentGroup::where('subscriber_id',$subscriber_id)->where('course_id',$level->course_id)->get();
        return view('employer.projects.edit', [
            'title' => 'title',
            'edit' => $project,
            'users'  => $users,
            'groups' => $groups,
            'choose' => $choose,
            'level' => $level,
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
        $project = Project::where('id',$id)->where('subscriber_id',$subscriber_id)->firstOrFail();
        //Make Validation
        $this->rules['title'] = 'required|max:200';
        $this->rules['description'] = 'sometimes|nullable';
        $this->rules['start_date'] = 'required|date';
        $this->rules['end_date'] = 'required|date|after:start_date';
        $this->rules['total'] = 'required|integer';
        $this->rules['image'] = 'sometimes|nullable|mimes:doc,csv,xlsx,xls,docx,ppt,odt,ods,odp,pdf,zip,png,jpg,gif,jpeg';
        $this->rules['file_upload'] = 'required|in:0,1';
        $this->rules['user_id'] = 'sometimes|nullable|array|exists:users,id';
        $this->rules['group_id'] = 'sometimes|nullable|array|exists:student_groups,id';
        $data = $this->validate($request, $this->rules);
        //Create Project
        $level = Level::where('id',$project->level_id)->where('subscriber_id',$subscriber_id)->firstOrFail(); //For Check
        // Fixed Data
        $data['user_id'] = User::where('type','1')->where('subscriber_id',$subscriber_id)->first()->id;
        $data['level_id'] = $project->level_id;
        $data['subscriber_id'] = $subscriber_id;
        if($request->start_date){
            $data['start_date'] = date('Y-m-d H:i:s',strtotime($request->start_date));
        }
        if($request->end_date){
            $data['end_date'] = date('Y-m-d H:i:s',strtotime($request->end_date));
        }
        // Start Upload File
        if ($request->hasFile('image')) {
           if (file_exists(public_path('uploads/' . $project->file))) {
               @unlink(public_path('uploads/' . $projec->file));
           }
            $destination = public_path('uploads/uploads/'). $subscriber_id . "/projects/" . date("Y") . "/" . date("m") . "/";
            $destination_database = "uploads/" . $subscriber_id . "/projects/" . date("Y") . "/" . date("m") . "/";
            //Setting For Name file and Path
            $file = request()->file('image');
            $name = $file->getClientOriginalName(); // get image name
            $extension = $file->getClientOriginalExtension(); // get image extension
            $sha1 = sha1($name); // hash the image name
            $random = rand(1, 1000000); // Random To Name
            $name_database = $random . "_" . date("y-m-d-h-i-s") . "_" . $sha1; // To use it without extension
            $fileName = $random . "_" . date("y-m-d-h-i-s") . "_" . $sha1 . "." . $extension; // create new name for the file
            $file->move($destination, $fileName); // Upload Attachment
            $data['file'] = $destination_database . $fileName; // Create Name To Send It
        }
        // End Upload File
        //Update Data
        $project->update($data);
        ProjectUser::where('project_id',$id)->delete();
            //Assign Users To Project
            if(!empty($request->group_id)){
                foreach ($request->group_id as $key => $group) {
                    $user_group = GroupUser::where('student_group_id',$group)->get();
                    foreach ($user_group as $key => $user) {
                        ProjectUser::UpdateOrCreate(
                            ['project_id'  => $id,
                            'user_id'     => $user->user_id]
                        );
                    }
                }
            }

            if(!empty($request->user_id)){
                foreach ($request->user_id as $key => $user) {
                    ProjectUser::UpdateOrCreate(
                        ['project_id'  => $id,
                        'user_id'     => $user]
                    );
                }
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
        $project = Project::where('id',$id)->where('subscriber_id',$subscriber_id)->firstOrFail();
        $project->delete();
    }



}
