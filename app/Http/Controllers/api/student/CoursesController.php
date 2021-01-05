<?php

namespace App\Http\Controllers\api\student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Father\User;
use App\Father\Course;
use App\Father\CourseUser;
use App\Classroom;
use App\DiscussionUser;
use App\Discussion;
use DB;

class CoursesController extends Controller
{

       /**
         * [Courses]
         * api_url: api/student/courses  [method:get]
         * @return [json]
         */
        public function index()
        {
            $course = Course::whereHas('courseUser',function($query){
                $query->where('user_id',auth()->id());
            })->with('category:id,name')->where('status','1')->orderBy('id','desc')->limit(100)->get();
            $courses = $course->map(function($course){
                if ($course->start_date > date('Y-m-d')){
                    $status = trans('admin.soon')." ".$course->start_date;
                    $link = "";
                }elseif ($course->end_date < date('Y-m-d') && $course->start_date <= date('Y-m-d')){
                    $status = trans('admin.expired');
                    $link = route('api.student.courses',[$course->id]);
                }else{
                    $status = trans('admin.show');
                    $link = route('api.student.courses',[$course->id]);
                }
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'logo' => $course->logo,
                    'status' => $status,
                    'link' => $link,
                    'start_date' => $course->start_date,
                    'end_date' => $course->end_date,
                    'description' => $course->description,
                    'object' => $course->object,
                    'video' => $course->video,
                    'category_id' => $course->category_id,
                    'category_name' => $course->category->name,
                ];
            });
            return sendResponse(trans('admin.courses'),$courses);
        }


        /**
          * [Courses]
          * @param [Course_id]
          * api_url: api/student/courses/show/{course_id}  [method:get]
          * @return [json]
          */

          public function show($course_id)
          {
              $course = Course::where('id',$course_id)->with('level')->where('subscriber_id',auth()->user()->subscriber_id)->whereHas('courseUser',function($query){
                  $query->where('user_id',auth()->id());
              })->where('start_date','<=',date('Y-m-d'))->first();
              if(!$course){
                  return sendError(trans('login.Please Check Your Data'));
              }
              $courses =  [
                      'id'         => $course->id,
                      'title'      => $course->title,
                      'logo'       => $course->logo,
                      'video'      => $course->video,
                      'classrooms' => route('api.student.rooms',[$course->id]),
                      'discussions'=> route('api.student.discussions',[$course->id]),
                      'exams'      => route('api.student.exam',[$course->id]),
                      'level'      => $course->level->map(function($level){
                          return [
                              'id'         => $level->id,
                              'title'      => $level->title,
                              'photos'     => route('api.student.photo',[$level->id]),
                              'videos'     => route('api.student.video',[$level->id]),
                              'attachments'=> route('api.student.attachment',[$level->id]),
                              'scorms'     => route('api.student.scorm',[$level->id]),
                              'projects'   => route('api.student.project',[$level->id]),
                          ];
                      }),
                  ];

              return sendResponse(trans('admin.courses'),$courses);
          }


          /**
            * [Class Rooms]
            * @param [Course_id]
            * api_url: api/student/rooms/{course_id}  [method:get]
            * @return [json]
            */

            public function rooms($course_id)
            {
                $rooms = Classroom::where('course_id',$course_id)->where('subscriber_id',auth()->user()->subscriber_id)->whereHas('course.courseUser',function($query){
                    $query->where('user_id',auth()->id())->where('start_date','<=',date('Y-m-d'));
                })->limit(50)->get(['id','title','start_date','end_date','class_no','link']);
                return sendResponse(trans('admin.virtual classes'),$rooms);
            }

            /**
              * [Class Rooms]
              * @param [Course_id]
              * api_url: api/student/discussions/{course_id}  [method:get]
              * @return [json]
              */

              public function discussions($course_id)
              {
                  $discussion = Discussion::with('discussion.user')->where('course_id',$course_id)->where('subscriber_id',auth()->user()->subscriber_id)->whereHas('course.courseUser',function($query){
                      $query->where('user_id',auth()->id())->where('start_date','<=',date('Y-m-d'));
                  })->limit(50)->get(['id','title']);
                  $discussions = $discussion->map(function($discussion){
                      return[
                          'id'          => $discussion->id ?? '',
                          'title'       => $discussion->title ?? '',
                          'discussion'  => $discussion->discussion->map(function($dis){
                              if($dis->type == '1'){
                                  $type = trans('admin.supervisor');
                              }elseif ($dis->type == '2') {
                                  $type = trans('admin.trainer');
                              }elseif ($dis->type == '3' && $dis->user_id == auth()->id()) {
                                  $type = 'Self';
                              }else{
                                  $type = trans('admin.student');
                              }
                              return [
                                  'id'        => $dis->id ?? '',
                                  'comment'   => $dis->comment ?? '',
                                  'type'      => $type ?? '',
                                  'name'      => $dis->user->name ?? '',
                                  'date'      => date('Y-m-d H:i',strtotime($dis->created_at)) ?? '',
                              ];
                          }),
                      ];
                  });
                  return sendResponse(trans('admin.discussions'),$discussions);
              }


              /**
               * [send Comment From Student in Discussions]
               * @param  Request $request [discussion_id,comment]
               * api_url: api/student/discussions/send [method:post]
               * @return [json]           [message data and message success]
               */
              public function send(Request $request)
              {
                  //Make Validation
                  $validator = \Validator::make($request->all(), [
                      "discussion_id" => "required|exists:discussions,id",
                      'comment'     =>'required',
                  ]);
                  //If Validation Errors
                  if ($validator->fails()) {
                      return sendError(implode(',',$validator->errors()->all()));
                  }
                  //Check Discussion ID
                  $discussions = Discussion::where('id',$request->discussion_id)->where('subscriber_id',auth()->user()->subscriber_id)->whereHas('course.courseUser',function($query){
                      $query->where('user_id',auth()->id())->where('start_date','<=',date('Y-m-d'));
                  })->first(); //For Check
                  if(!$discussions){
                      return sendError(trans('login.Please Check Your Data'));
                  }
                  try{
                      $request['user_id']   = auth()->id();
                      $request['type']   = auth()->user()->type ?? '3';
                      $comment = DiscussionUser::Create($request->all());
                      return sendResponse(trans('admin.sent Successfully'),$comment);
                  //If Find Any Problem
                  }catch(Exception $e){
                         return sendError(trans('login.Please Check Your Data'));
                  }
              }

}
