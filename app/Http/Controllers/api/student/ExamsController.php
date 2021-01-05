<?php
namespace App\Http\Controllers\api\student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Father\User;
use App\ExamUser;
use App\Exam;
use App\ExamBank;
use App\Bank;
use App\ExamAttempt;
use App\Father\CourseUser;

class ExamsController extends Controller
{

    /**
    * [exam index]
    * @param  [int] $course_id
    * route(api.student.exam)
    * api_url: api/student/exams/{course_id}  [method:get]
    * @return [json]
    */
    public function index($course_id)
    {
        $exams = Exam::where('subscriber_id',auth()->user()->subscriber_id)->where('course_id',$course_id)->whereHas('course.courseUser',function($query){
            $query->where('user_id',auth()->id())->where('start_date','<=',date('Y-m-d'));
        })->with('level:id,title','examUser')->whereHas('examUser',function($query){
            $query->where('user_id',auth()->id());
        })->with(['examUser' => function($query){
            $query->where('user_id',auth()->id());
        }])->get();
        $data = $exams->map(function($exam){
            if(($exam->try_no < $exam->examUser[0]->try) || $exam->start_date > date('Y-m-d') || $exam->end_date < date('Y-m-d')){
                $can_answer = 0;
            }else{
                $can_answer = 1;
            }
            if(date('Y-m-d') > $exam->end_date){
                $show_best_answer = 1;
            }else{
                $show_best_answer = 0;
            }
            return [
                'id'              => $exam->id ?? '',
                'title'           => $exam->title ?? '',
                'description'     => $exam->description ?? '',
                'start_date'      => $exam->start_date ?? '',
                'end_date'        => $exam->end_date ?? '',
                'time_minutes'    => $exam->time_minutes ?? '',
                'success_average' => $exam->success_average ?? '',
                'course_id'       => $exam->course_id ?? '',
                'level'           => !empty($exam->level) ? $exam->level->title ?? '' : trans('admin.comprehensive') ?? '',
                'grade'           => $exam->examUser[0]->grade ?? '',
                'total'           => $exam->examUser[0]->total ?? '',
                'trainer_notes'   => $exam->examUser[0]->notes ?? '',
                'try_no'          => $exam->examUser[0]->try.'/'.$exam->try_no ?? '',
                'can_answer'      => $can_answer,
                'can_answer_link' => $can_answer == 1 ? route('api.student.exam.start',[$exam->id]) : '',
                'show_best_answer'=> $show_best_answer,
                'show_best_answer_link'=> $show_best_answer == 1 ? route('api.student.exam.best',[$exam->id]) : '',
                ];
            });
            return sendResponse(trans('admin.exams'),$data);
        }


        /**
        * [Best Answer Of Exam]
        * @param  [int] $id
        * route(api.student.exam.best)
        * api_url: api/student/exams/best/{id}  [method:get]
        * @return [json]
        */
        public function best($id)
        {
            $exam = Exam::with('examBank.bank')->with(['examUser' => function($query){
                $query->where('user_id',auth()->id());
            }])->where('id',$id)->whereHas('course.courseUser',function($query){
                $query->where('user_id',auth()->id());
            })->first();
            if(!$exam || date('Y-m-d') <= $exam->end_date){
                return sendError(trans('login.Please Check Your Data'));
            }
            $merge = $exam->examBank->map(function($bank) use($id){
                $attempt = ExamAttempt::where('exam_id',$id)->where('user_id',auth()->id())->where('bank_id',$bank->bank_id)->first();
                return [
                'answer_grade' => $attempt->answer_grade ?? "0",
                'answer' => $attempt->answer ?? "",
                'question' => $bank->bank->title ?? "",
                'answers' => @explode('|',$bank->bank->answers) ?? "",
                'best_answer' => $bank->bank->best_answer ?? "",
                'grade' => $bank->bank->grade ?? "",
                ];
            });
            return sendResponse(trans('admin.best_answers'),$merge);
        }

        /**
        * [Best Answer Of Exam]
        * @param  [int] $id
        * route(api.student.exam.question)
        * api_url: api/student/exams/question/{id}  [method:get]
        * @return [json]
        */
        public function question($id)
        {
            $exam = Exam::with('examBank.bank')->with(['examUser' => function($query){
                $query->where('user_id',auth()->id());
            }])->where('id',$id)->whereHas('course.courseUser',function($query){
                $query->where('user_id',auth()->id());
            })->first();
            if(!$exam){
                return sendError(trans('login.Please Check Your Data'));
            }
            $merge = $exam->examBank->map(function($bank) use($id){
                $attempt = ExamAttempt::where('exam_id',$id)->where('user_id',auth()->id())->where('bank_id',$bank->bank_id)->first();
                return [
                'question_id' => $bank->bank->id ?? "",
                'question' => $bank->bank->title ?? "",
                'answers' => @explode('|',$bank->bank->answers) ?? "",
                'grade' => $bank->bank->grade ?? "",
                ];
            });
            return sendResponse(trans('admin.exams'),$merge);
        }

        /**
        * [Start Exam]
        * @param  [int] $id
        * route(api.student.exam.start)
        * api_url: api/student/exams/start/{id}  [method:get]
        * @return [json]
        */
        public function start($id)
        {
            $exam = Exam::with('examBank.bank')->where('id',$id)->whereHas('course.courseUser',function($query){
                $query->where('user_id',auth()->id());
            })->first();
            $exam_user = ExamUser::where('exam_id',$id)->where('user_id',auth()->id())->first();
            if(!$exam || !$exam_user ||($exam->try_no <= $exam_user->try) || $exam->start_date > date('Y-m-d') || $exam->end_date < date('Y-m-d')){
                return sendError(trans('login.Please Check Your Data'));
            }
            $ex = ExamUser::where('exam_id',$id)->where('user_id',auth()->id())->first();
            $data = ['start_date' => date('Y-m-d H:i:s',strtotime($ex->updated_at)),'link_check' => route('api.student.exam.check',[$ex->id])];
            return sendResponse(trans('admin.exams'),$data);
        }


        /**
        * [Check Time Exam]
        * @param  [int] $id
        * route(api.student.exam.check)
        * api_url: api/student/exams/check/{id}  [method:get]
        * @return [json]
        */
        public function check($id)
        {
            $exam = Exam::with('examBank.bank')->where('id',$id)->whereHas('course.courseUser',function($query){
                $query->where('user_id',auth()->id());
            })->first();
            $exam_user = ExamUser::where('exam_id',$id)->where('user_id',auth()->id())->first();

            $end_date = date('Y-m-d H:i:s',strtotime($exam_user->updated_at.'+ '.$exam->time_minutes.' minute'));
            if(!$exam_user || !$exam){
                return sendError(trans('login.Please Check Your Data'));
            }
            if ($end_date >= date('Y-m-d H:i:s')) { // 2019-12-10 02:10:30 >= 2019-12-10 02:07:30
                $data = [
                'check_time' => true,
                ];
            }else{
                $data = [
                'check_time' => false,
                ];
            }
            return sendResponse(trans('admin.exams'),$data);
        }


        /**
        * [Answer Exam From Student]
        * @param  Request $request [question_id,answer]
        * api_url: api/student/exams/answer/{exam_id} [method:post]
        * route(api.student.exam.answer)
        * @return [json]           [message data and message success]
        */
        public function send(Request $request,$id)
        {
            $ex = Exam::with('examBank.bank')->where('id',$id)->whereHas('course.courseUser',function($query){
                $query->where('user_id',auth()->id());
            })->first();
            $exam_user = ExamUser::where('exam_id',$id)->where('user_id',auth()->id())->first();
            if(!$ex || !$exam_user ||($ex->try_no <= $exam_user->try) || $ex->start_date > date('Y-m-d') || $ex->end_date < date('Y-m-d')){
                return sendError(trans('login.Please Check Your Data'));
            }
            $exam_user->increment('try');
            try{
                //Make Validation
                $validator = \Validator::make($request->all(), [
                "question_id" => "required|array",
                'question_id.*'     =>'required|exists:banks,id',
                'answer'     =>'required|array',
                'answer.*'     =>'required',
                ]);
                //If Validation Errors
                if ($validator->fails()) {
                    return sendError(implode(',',$validator->errors()->all()));
                }
                //Account Total grades of Questions
                $exam = ExamBank::with('bank')->where('exam_id',$id)->get();
                $sum = [];
                foreach ($exam as $bank) {
                    $sum[] = $bank->bank->grade;
                }
                $total = array_sum($sum);
                $bank_id = $exam->pluck('bank_id');
                // For each and insert question by question
                $exam_grade = [];
                ExamAttempt::where('exam_id',$id)->where('user_id',auth()->id())->delete();
                if(!empty($request->answer)){
                    foreach ($request->answer as $key => $answer) {
                        if($bank_id->contains($request->question_id[$key])){
                            $bank = Bank::where('id',$request->question_id[$key])->first();
                            if($bank->best_answer == $answer){
                                $grade = $bank->grade;
                            }else{
                                $grade = '0';
                            }
                            ExamAttempt::create([
                            'answer' => $answer,
                            'bank_id' => $request->question_id[$key],
                            'user_id' => auth()->id(),
                            'exam_id' => $id,
                            'bank_grade' => $bank->grade,
                            'answer_grade' => $grade,
                            ]
                            );
                            $exam_grade[] = $grade;
                        }

                    }
                }
                //Account Student Grades
                $ex_grade = array_sum($exam_grade);
                $exam_user->update(['grade' => $ex_grade,'total' => $total]);
                return sendResponse(trans('admin.add Successfully'),$exam_user);
                //If Find Any Problem
            }catch(Exception $e){
                return sendError(trans('login.Please Check Your Data'));
            }
        }

    }
