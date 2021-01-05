<?php

namespace App\Http\Controllers\Super;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ExamUser;
use App\CourseUser;
use App\AttachmentCourse;
use App\VideoCourse;
use App\PhotoCourse;
use App\ScormCourse;
use App\ProjectUser;
use App\Project;
use App\Course;
use App\User;
use App\Exam;


class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function trainer($id)
    {
        // Check Trainer in This School
        $user = User::with('subscriber')->where('id',$id)->where('type','2')->where('subscriber_id',auth()->user()->subscriber_id)->firstOrFail();
        // Get Courses
        $courses = Course::whereHas('courseUser',function($query) use($id){
            $query->where('user_id',$id);
        })->get();
        $reports = $courses->map(function($course) use($id){
            // Projects Count
            $project = Project::whereHas('level',function($query) use($course,$id){
                $query->where('course_id',$course->id);
            })->where('user_id',$id)->count();
            // Videos Count
            $video = VideoCourse::whereHas('level',function($query) use($course,$id){
                $query->where('course_id',$course->id);
            })->where('user_id',$id)->count();
            // Photos Count
            $photo = PhotoCourse::whereHas('level',function($query) use($course,$id){
                $query->where('course_id',$course->id);
            })->where('user_id',$id)->count();
            // Attachments Count
            $attachment = AttachmentCourse::whereHas('level',function($query) use($course,$id){
                $query->where('course_id',$course->id);
            })->where('user_id',$id)->count();
            // Scorms Count
            $scorm = ScormCourse::whereHas('level',function($query) use($course,$id){
                $query->where('course_id',$course->id);
            })->where('user_id',$id)->count();
            // Exam Count
            $exam = Exam::where('course_id',$course->id)->where('user_id',$id)->count();
            // Return All Data
            return [
                'title'      => $course->title,
                'project'    => $project,
                'video'      => $video,
                'photo'      => $photo,
                'attachment' => $attachment,
                'scorm'      => $scorm,
                'exam'      => $exam,
            ];

        });


        // Videos Count
        $video = VideoCourse::where('user_id',$id)->count();
        // Photos Count
        $photo = PhotoCourse::where('user_id',$id)->count();
        // Attachments Count
        $attachment = AttachmentCourse::where('user_id',$id)->count();
        // Scorms Count
        $scorm = ScormCourse::where('user_id',$id)->count();
        return view('super.reports.trainer', [
            'reports' => $reports,
            'user'    => $user,
            'video'    => $video,
            'photo'    => $photo,
            'scorm'    => $scorm,
            'attachment'    => $attachment,
        ]);
    }

    /**
     * Display a listing of the resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function student($id)
    {
        // Check Trainer in This School
        $user = User::with('subscriber')->where('id',$id)->where('type','3')->where('subscriber_id',auth()->user()->subscriber_id)->firstOrFail();
        // Get Courses
        $courses = CourseUser::with('course')->where('user_id',$id)->get();
        $reports = $courses->map(function($course) use($id){
            // Projects Count
            $projects = ProjectUser::with('project')->where('user_id',$id)->whereHas('project.level',function($query) use($course){
                $query->where('course_id',$course->course_id);
            })->get();
            // Exam Count
            $exams = ExamUser::with('exam')->where('user_id',$id)->whereHas('exam',function($query) use($course){
                $query->where('course_id',$course->course_id);
            })->get();
            // Return All Data
            return [
                'title'      => $course->course->title ?? "",
                'projects'    => $projects,
                'video'      => $course->count_video,
                'photo'      => $course->count_photo,
                'attachment' => $course->count_attachment,
                'scorm'      => $course->count_scorm,
                'exams'      => $exams,
            ];

        });
        $chart = ExamUser::with('exam')->where('user_id',$id)->whereNotNull('grade')->orderBy('id','desc')->limit(12)->get();

        return view('super.reports.student', [
            'reports' => $reports,
            'user'    => $user,
            'chart'    => $chart,
            'video'    => $courses->sum('count_video'),
            'photo'    => $courses->sum('count_photo'),
            'scorm'    => $courses->sum('count_scorm'),
            'attachment'    => $courses->sum('count_attachment'),
        ]);
    }




}
