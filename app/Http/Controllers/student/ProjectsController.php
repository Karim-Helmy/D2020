<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
    public function index($level_id)
    {
        $projects = ProjectUser::whereHas('project',function($query) use($level_id){
            $query->where('subscriber_id',auth()->user()->subscriber_id)->where('level_id',$level_id)->whereHas('level.course.courseUser',function($query){
                $query->where('user_id',auth()->id());
            })->with('level.course:id,title')->whereDate('start_date','<=',date('Y-m-d'));
        })->where('user_id',auth()->id())->with('project')->paginate(25);
        $course_id = Level::where('id',$level_id)->firstOrFail()->course_id;
        return view('student.projects.index', [
            'projects' => $projects,
            'id' => $level_id,
            'course_id' => $course_id,
        ]);
    }


    /**
     * Show the form for create the specified resource.
     * $id
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $project = Project::where('subscriber_id',auth()->user()->subscriber_id)->where('id',$id)->whereHas('level.course.courseUser',function($query){
            $query->where('user_id',auth()->id());
        })->whereHas('projectUser',function($query){
            $query->where('user_id',auth()->id());
        })->whereDate('start_date','<=',date('Y-m-d'))->whereDate('end_date','>=',date('Y-m-d'))->with('level')->with('level')->firstOrFail();
        $projectUser = ProjectUser::where('user_id',auth()->id())->where('project_id',$id)->whereNull('grade')->firstOrFail();
        return view('student.projects.create', [
            'project' => $project,
            'projectUser' => $projectUser,
        ]);
    }


    /**
    * Store a newly created resource in storage.
    * @param  int  $id
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request,$id)
   {
       //Make Validation
       $this->rules['answer'] = 'required';
       $this->rules['image'] = 'sometimes|nullable|mimes:doc,csv,xlsx,xls,docx,ppt,odt,ods,odp,pdf,zip,png,jpg,gif,jpeg';
       $data = $this->validate($request, $this->rules);
       $project = ProjectUser::where('user_id',auth()->id())->where('project_id',$id)->whereNull('grade')->firstOrFail();
       // Start Upload File
       if ($request->hasFile('image')) {
           $destination = public_path('uploads/uploads/'). auth()->user()->subscriber_id . "/projects/" . date("Y") . "/" . date("m") . "/";
           $destination_database = "uploads/" . auth()->user()->subscriber_id . "/projects/" . date("Y") . "/" . date("m") . "/";
           //Setting For Name file and Path
           $file = request()->file('image');
           $name = $file->getClientOriginalName(); // get image name
           $extension = $file->getClientOriginalExtension(); // get image extension
           $sha1 = sha1($name); // hash the image name
           $random = rand(1, 1000000); // Random To Name
           $name_database = $random . "_" . date("y-m-d-h-i-s") . "_" . $sha1; // To use it without extension
           $fileName = $random . "_" . date("y-m-d-h-i-s") . "_" . $sha1 . "." . $extension; // create new name for the file
           $file->move($destination, $fileName); // Upload Attachment
           $data['answer_file'] = $destination_database . $fileName; // Create Name To Send It
       }
       // End Upload File
       $project->update($data);

       session()->flash('success', trans("admin.add Successfully"));
       return redirect()->back();
   }


   /**
    * Display the specified resource.
    *
    * @param  int  $level_id
    * @return \Illuminate\Http\Response
    */
   public function show($id)
   {
       $project = ProjectUser::with('project.level')->where('user_id',auth()->id())->where('project_id',$id)->whereNotNull('grade')->firstOrFail();
       return view('student.projects.show', [
           'project' => $project,
       ]);
   }

}
