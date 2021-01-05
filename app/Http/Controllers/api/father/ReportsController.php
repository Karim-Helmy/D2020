<?php

namespace App\Http\Controllers\api\father;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Father\User;
use App\ExamUser;
use App\ProjectUser;
use App\StudentView;
use App\Father\CourseUser;
use DB;

class ReportsController extends Controller
{

       /**
         * [Activities And Reports]
         * @param  [int] $user_id [User ID (son id)]
         * api_url: api/father/activities/{user_id}  [method:get]
         * @return [json]
         */
        public function index($id)
        {
            if (request()->filled('date_from')) {
               $from = request()->date_from;
           }else{
               $from = date('Y-m-d');
           }
           if (request()->filled('date_to')) {
               $to = request()->date_to;
           }else{
               $to = $from;
           }
            // Check Trainer in This School
            $user = User::with('subscriber')->where('id',$id)->where('type','3')->where('father_id',auth()->id())->first();
            if(!$user){
                return sendError(trans('login.Please Check Your Data'));
            }
            // Get Courses
            $courses = CourseUser::with('course')->where('user_id',$id)->get();
            $reports = $courses->map(function($course) use($user,$from,$to){
                // Projects
               if (request()->filled('date_from') || request()->filled('date_to')) {
                   $project = ProjectUser::with('project')->where('user_id',$user->id)->whereHas('project.level',function($query) use($course){
                      $query->where('course_id',$course->course_id);
                  })->whereBetween(DB::raw('date(created_at)'), [$from, $to])->get();
               }else{
                   $project = ProjectUser::with('project')->where('user_id',$user->id)->whereHas('project.level',function($query) use($course){
                      $query->where('course_id',$course->course_id);
                  })->get();
               }
                //Filter Project Data
                $projects = $project->map(function($p){
                    return [
                        "id"            => $p->project->id,
                        "name"          => $p->project->title,
                        "student_grade" => $p->grade ?? null,
                        "final_grade"   => $p->project->total ?? null,
                        "percentage"    => $p->grade ? floor(($p->grade / $p->project->total)*100) : null,
                        "start_date"    => $p->project->start_date,
                        "end_date"      => $p->project->end_date,
                    ];
                });
                // Exams

                if (request()->filled('date_from') || request()->filled('date_to')) {
                   $exam = ExamUser::with('exam')->where('user_id',$user->id)->whereHas('exam',function($query) use($course){
                       $query->where('course_id',$course->course_id);
                   })->whereBetween(DB::raw('date(created_at)'), [$from, $to])->get();
                }else{
                    $exam = ExamUser::with('exam')->where('user_id',$user->id)->whereHas('exam',function($query) use($course){
                        $query->where('course_id',$course->course_id);
                    })->get();
                }
                //Filter Project Data
                $exams = $exam->map(function($e){
                    return [
                        "id"            => $e->exam->id,
                        "name"          => $e->exam->title,
                        "student_grade" => $e->grade ?? null,
                        "final_grade"   => $e->total ?? null,
                        "percentage"    => $e->grade ? floor(($e->grade / $e->total)*100) : null,
                        "start_date"    => $e->exam->start_date,
                        "end_date"      => $e->exam->end_date,
                    ];
                });

                $supervisor = User::where('subscriber_id',$user->subscriber->id)->where('type','1')->first();
                $course_level = collect($course->course->level)->pluck('id');
                if (request()->filled('date_from') || request()->filled('date_to')) {
                    $video_count      = StudentView::where('type','video')->where('user_id',$user->id)->whereBetween(DB::raw('date(created_at)'), [$from, $to])->whereIN('type_id',$course_level)->count();
                    $photo_count      = StudentView::where('type','photo')->where('user_id',$user->id)->whereBetween(DB::raw('date(created_at)'), [$from, $to])->whereIN('type_id',$course_level)->count();
                    $attachment_count = StudentView::where('type','attachment')->where('user_id',$user->id)->whereBetween(DB::raw('date(created_at)'), [$from, $to])->whereIN('type_id',$course_level)->count();
                    $scorm_count      = StudentView::where('type','scorm')->where('user_id',$user->id)->whereBetween(DB::raw('date(created_at)'), [$from, $to])->whereIN('type_id',$course_level)->count();

                }else{
                    $video_count = $course->count_video;
                    $photo_count = $course->count_photo;
                    $attachment_count = $course->count_attachment;
                    $scorm_count = $course->count_scorm;
                }
                // Return All Data
                return [
                    'course_name'       => $course->course->title ?? "",
                    'course_id'         => $course->course->id ?? "",
                    'course_photo'      => $course->course->logo ?? "",
                    'school_name'       => $user->subscriber->school ?? "",
                    'son_id'            => $user->id ?? "",
                    'student_name'      => $user->name ?? "",
                    'supervisor_id'     => $supervisor->id ?? "",
                    'supervisor_name'   => $supervisor->name ?? "",
                    'count_videos'      => $video_count,
                    'count_images'      => $photo_count,
                    'count_attachments' => $attachment_count,
                    'Reading rate'      => $scorm_count,
                    'exams'             => $exams,
                    'projects'          => $projects,
                ];
            });
            return sendResponse(trans('admin.reports'),$reports);
        }

}
