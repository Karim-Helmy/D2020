<?php

namespace App\Http\Controllers\api\student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Father\User;
use App\ProjectUser;
use App\Project;
use App\Father\CourseUser;

class ProjectsController extends Controller
{

    /**
     * [project index]
     * @param  [int] $level_id
     * route(api.student.project)
     * api_url: api/student/projects/{level_id}  [method:get]
     * @return [json]
     */
    public function index($level_id)
    {
        $projects = ProjectUser::whereHas('project',function($query) use($level_id){
            $query->where('subscriber_id',auth()->user()->subscriber_id)->where('level_id',$level_id)->whereHas('level.course.courseUser',function($query){
                $query->where('user_id',auth()->id())->where('start_date','<=',date('Y-m-d'));
            })->with('level.course:id,title')->whereDate('start_date','<=',date('Y-m-d'));
        })->where('user_id',auth()->id())->with('project')->limit(100)->get();
        $data = $projects->map(function($project){
            return [
                'id'          => $project->project->id,
                'title'       => $project->project->title,
                'description' => $project->project->description,
                'start_date'  => $project->project->start_date,
                'end_date'    => $project->project->end_date,
                'total'       => $project->project->total,
                'file_upload' => $project->project->file_upload == '1' ? trans('admin.yes') : trans('admin.no'),
                'file'        => $project->project->file ? asset('uploads/'.$project->project->file) : '',
                'answer'      =>[
                    'my_answer'      => $project->answer ?? '',
                    'my_grade'       => $project->grade ? $project->grade.'/'.$project->project->total ?? '' : '',
                    'trainer_notes'  => $project->notes ?? '',
                    'answer_file'    => $project->answer_file ? asset('uploads/'.$project->answer_file) : '',
                    'send_answer'    => $project->grade ? '' : route('api.student.project.send'),
                ]
            ];
        });
        return sendResponse(trans('admin.projects'),$data);
    }


    /**
     * [Send Project From Student]
     * @param  Request $request [project_id,answer,image]
     * api_url: api/student/projects/send [method:post]
     * route(api.student.project.send)
     * @return [json]           [message data and message success]
     */
    public function send(Request $request)
    {
        //Make Validation
        $validator = \Validator::make($request->all(), [
            "project_id" => "required|exists:projects,id",
            'answer'     =>'required',
            'image'      =>'sometimes|nullable|mimes:doc,csv,xlsx,xls,docx,ppt,odt,ods,odp,pdf,zip,png,jpg,gif,jpeg',
        ]);
        //If Validation Errors
        if ($validator->fails()) {
            return sendError(implode(',',$validator->errors()->all()));
        }
        $project = ProjectUser::with('project')->whereHas('project',function($query){
            $query->whereDate('start_date','<=',date('Y-m-d'))->whereDate('end_date','>=',date('Y-m-d'));
        })->where('user_id',auth()->id())->where('project_id',$request->project_id)->whereNull('grade')->first();
        if(!$project){
            return sendError(trans('login.Please Check Your Data'));
        }
        if($project->project->file_upload == '1'){
            // Start Upload File
            if ($request->hasFile('image')) {
                $destination = "uploads/uploads/" . auth()->user()->subscriber_id . "/projects/" . date("Y") . "/" . date("m") . "/";
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
                $request['answer_file'] = $destination_database . $fileName; // Create Name To Send It
            }
            // End Upload File
        }
        try{
            $project->update($request->all());
            return sendResponse(trans('admin.add Successfully'),$project);
        //If Find Any Problem
        }catch(Exception $e){
               return sendError(trans('login.Please Check Your Data'));
        }
    }

}
