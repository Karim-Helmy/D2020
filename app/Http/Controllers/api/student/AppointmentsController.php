<?php

namespace App\Http\Controllers\api\student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Course;
use App\Exam;
use App\Classroom;
use App\ProjectUser;
use Carbon\Carbon;

class AppointmentsController extends Controller
{

    /**
     * [Appointments index]
     * route(api.student.appointment)
     * api_url: api/student/appointments/  [method:get]
     * @return [json]
     */
    public function index()
    {
        if(request()->status == '1' ){
            $start = date('Y-m-d');
            $end   = date('Y-m-d');
        }elseif (request()->status == '2') {
            $start = Carbon::now()->startOfWeek();
            $end   = Carbon::now()->endOfWeek();
        }elseif (request()->status == '3') {
            $start = Carbon::today()->startOfMonth();
            $end   = Carbon::today()->endOfMonth();
        }else{
            return sendError(trans('login.Please Check Your Data'));
        }
        //Courses
        $course = Course::whereHas('courseUser',function($query){
            $query->where('user_id',auth()->id());
        })->with('category')->where('status','1')->where(function($q) use($end,$start) {
          $q->whereBetween('start_date',[$start,$end]);
      })->limit(40)->get();

      $courses = $course->map(function($c){
          return [
              'id'         => $c->id,
              'title'      => $c->title,
              'start_date' => $c->start_date,
              'end_date'   => $c->end_date,
          ];
      });

      //Class Rooms
      $room = Classroom::where(function($q) use($end,$start) {
        $q->whereBetween('start_date',[$start,$end]);
    })->where('subscriber_id',auth()->user()->subscriber_id)->whereHas('course.courseUser',function($query){
          $query->where('user_id',auth()->id());
      })->with('course')->limit(40)->get();

      $rooms = $room->map(function($r){
          return [
              'id'         => $r->id,
              'title'      => $r->title,
              'start_date' => $r->start_date,
              'end_date'   => $r->end_date,
          ];
      });

      //projects
      $project = ProjectUser::whereHas('project',function($query)use($end,$start){
          $query->where('subscriber_id',auth()->user()->subscriber_id)->whereHas('level.course.courseUser',function($query) use($end,$start){
              $query->where('user_id',auth()->id());
          })->where(function($q) use($end,$start) {
            $q->whereBetween('start_date',[$start,$end]);
        });
    })->where('user_id',auth()->id())->with('project.level.course')->limit(40)->get();

    $projects = $project->map(function($p){
        return [
            'id'         => $p->project->id,
            'title'      => $p->project->title,
            'start_date' => $p->project->start_date,
            'end_date'   => $p->project->end_date,
        ];
    });

      //Exams
      $exam = Exam::where(function($q) use($end,$start) {
        $q->whereBetween('start_date',[$start,$end]);
    })->where('subscriber_id',auth()->user()->subscriber_id)->whereHas('course.courseUser',function($query){
          $query->where('user_id',auth()->id());
      })->whereHas('examUser',function($query){
          $query->where('user_id',auth()->id());
      })->with(['examUser' => function($query){
          $query->where('user_id',auth()->id());
      }])->with('course')->limit(40)->get();

      $exams = $exam->map(function($e){
          return [
              'id'         => $e->id,
              'title'      => $e->title,
              'start_date' => $e->start_date,
              'end_date'   => $e->end_date,
          ];
      });

      $data = [
          'courses'  => $courses,
          'rooms'    => $rooms,
          'projects' => $projects,
          'exams'    => $exams,
      ];
        return sendResponse(trans('admin.projects'),$data);
    }


}
