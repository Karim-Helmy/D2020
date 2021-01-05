<?php
namespace App\Http\Controllers\api\student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Father\User;
use App\Father\Course;
use App\StudentView;
use App\PhotoCourse;
use App\AttachmentCourse;
use App\Photo;
use App\Level;
use App\Father\CourseUser;
use App\VideoCourse;
use App\ScormCourse;
use DB;

class MediaController extends Controller
{

       /**
         * [Photos]
         * @param [$level_id]
         * route(api.student.photo)
         * api_url: api/student/photos/{level_id}  [method:get]
         * @return [json]
         */
        public function photo($level_id)
        {
            // Check Permission
            $check = Level::where('id',$level_id)->whereHas('course.courseUser',function($query){
                $query->where('user_id',auth()->id())->where('start_date','<=',date('Y-m-d'));
            })->with(['course.courseUser' => function($query){
                $query->where('user_id',auth()->id());
            }])->first();
            if(!$check){
                return sendError(trans('login.Please Check Your Data'));
            }
            //Check Views
            $views = StudentView::firstOrCreate(
                ['type' => 'photo', 'type_id' => $level_id, 'view_date' => date('Y-m-d'), 'user_id' => auth()->id()]
            );
            if($views->wasRecentlyCreated){
                $course = CourseUser::where('course_id',$check->course_id)->where('user_id',auth()->id())->first();
                $course->increment('count_photo');
            }
            //Get Photos
            $photos = PhotoCourse::where('level_id',$level_id)->where('subscriber_id',auth()->user()->subscriber_id)->with('photo')->orderBy('id','desc')->get();
            $data = $photos->map(function($photo){
                return [
                    'title'   => $photo->photo->title,
                    'content' => asset('uploads/'.$photo->photo->image)
                ];
            });
            return sendResponse(trans('admin.Photos'),$data);
        }


        /**
          * [Attachments]
          * @param [$level_id]
          * route(api.student.attachment)
          * api_url: api/student/attachments/{level_id}  [method:get]
          * @return [json]
          */
         public function attachment($level_id)
         {
             // Check Permission
             $check = Level::where('id',$level_id)->whereHas('course.courseUser',function($query){
                 $query->where('user_id',auth()->id())->where('start_date','<=',date('Y-m-d'));
             })->with(['course.courseUser' => function($query){
                 $query->where('user_id',auth()->id());
             }])->first();
             if(!$check){
                 return sendError(trans('login.Please Check Your Data'));
             }
             //Check Views
             $views = StudentView::firstOrCreate(
                 ['type' => 'attachment', 'type_id' => $level_id, 'view_date' => date('Y-m-d'), 'user_id' => auth()->id()]
             );
             if($views->wasRecentlyCreated){
                 $course = CourseUser::where('course_id',$check->course_id)->where('user_id',auth()->id())->first();
                 $course->increment('count_attachment');
             }
             //Get Attachments
             $attachments = AttachmentCourse::where('level_id',$level_id)->where('subscriber_id',auth()->user()->subscriber_id)->with('attachment')->orderBy('id','desc')->get();
             $data = $attachments->map(function($attachment){
                 return [
                     'title'   => $attachment->attachment->title,
                     'content' => asset('uploads/'.$attachment->attachment->attachments)
                 ];
             });
             return sendResponse(trans('admin.attachments'),$data);
         }


         /**
           * [Videos]
           * @param [$level_id]
           * route(api.student.video)
           * api_url: api/student/videos/{level_id}  [method:get]
           * @return [json]
           */
          public function video($level_id)
          {
              // Check Permission
              $check = Level::where('id',$level_id)->whereHas('course.courseUser',function($query){
                  $query->where('user_id',auth()->id())->where('start_date','<=',date('Y-m-d'));
              })->with(['course.courseUser' => function($query){
                  $query->where('user_id',auth()->id());
              }])->first();
              if(!$check){
                  return sendError(trans('login.Please Check Your Data'));
              }
              //Check Views
              $views = StudentView::firstOrCreate(
                  ['type' => 'video', 'type_id' => $level_id, 'view_date' => date('Y-m-d'), 'user_id' => auth()->id()]
              );
              if($views->wasRecentlyCreated){
                  $course = CourseUser::where('course_id',$check->course_id)->where('user_id',auth()->id())->first();
                  $course->increment('count_video');
              }
              //Get Videos
              $videos = VideoCourse::where('level_id',$level_id)->where('subscriber_id',auth()->user()->subscriber_id)->with('video')->orderBy('id','desc')->get();
              $data = $videos->map(function($video){
                  return [
                      'title'   => $video->video->title,
                      'content' => $video->video->link
                  ];
              });
              return sendResponse(trans('admin.videos'),$data);
          }


          /**
            * [Scorms]
            * @param [$level_id]
            * route(api.student.scorm)
            * api_url: api/student/scorms/{level_id}  [method:get]
            * @return [json]
            */
           public function scorm($level_id)
           {
               // Check Permission
               $check = Level::where('id',$level_id)->whereHas('course.courseUser',function($query){
                   $query->where('user_id',auth()->id())->where('start_date','<=',date('Y-m-d'));
               })->with(['course.courseUser' => function($query){
                   $query->where('user_id',auth()->id());
               }])->first();
               if(!$check){
                   return sendError(trans('login.Please Check Your Data'));
               }
               //Check Views
               $views = StudentView::firstOrCreate(
                   ['type' => 'scorm', 'type_id' => $level_id, 'view_date' => date('Y-m-d'), 'user_id' => auth()->id()]
               );
               if($views->wasRecentlyCreated){
                   $course = CourseUser::where('course_id',$check->course_id)->where('user_id',auth()->id())->first();
                   $course->increment('count_scorm');
               }
               //Get Scorms
               $scorms = ScormCourse::where('level_id',$level_id)->where('subscriber_id',auth()->user()->subscriber_id)->with('scorm')->orderBy('id','desc')->get();
               $data = $scorms->map(function($scorm){
                   return [
                       'title'   => $scorm->scorm->title,
                       'content' => route('api.student.scorm.play',[$scorm->scorm->id*6554845205405485])
                   ];
               });
               return sendResponse(trans('admin.Scorms'),$data);
           }


           /**
             * [Scorms]
             * @param [$id]
             * route(api.student.scorm.play)
             * api_url: api/student/scorms/play/{id}  [method:get]
             * @return [json]
             */
           public function play($id)
           {
            $id = $id/6554845205405485;
           $scorms = ScormCourse::with('scorm')->where('scorm_id',$id)->first();
           if(!$scorms){
               return sendError(trans('login.Please Check Your Data'));
           }
           $path = public_path('uploads/'.$scorms->scorm->scorm.'/imsmanifest.xml');
           if(file_exists($path)){
               $xml = file_get_contents($path);
               $xml = simplexml_load_string($xml);
               $content = json_decode(json_encode($xml),TRUE);
               $link = $content['resources']['resource']['@attributes']['href'];
           }else{
               $link = "index.html";
           }
           return view('student.scorms.show', [
               'title' => trans("admin.scorms"),
               'scorm' => $scorms->scorm->scorm,
               'link' => $link,
           ]);

           }


}
