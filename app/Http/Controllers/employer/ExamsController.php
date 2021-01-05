<?php

namespace App\Http\Controllers\Employer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GroupUser;
use App\StudentGroup;
use App\CourseUser;
use App\ExamUser;
use App\ExamBank;
use App\Exam;
use App\Bank;
use App\User;
use App\Level;

class ExamsController extends Controller
{
    /**
     * Display the specified resource.
     *
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
        $exams = Exam::where('subscriber_id',$subscriber_id)->where('course_id',$course_id)->with('level:id,title')->get();
        return view('employer.exams.index', [
            'title' => trans('admin.exams'),
            'exams' => $exams,
            'id' => $course_id,
            'subscriber_id'    => $subscriber_id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,$subscriber_id)
    {
        if(!userSubscriber($subscriber_id)){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        $exam = Exam::with('examBank.bank')->where('id',$id)->where('subscriber_id',$subscriber_id)->firstOrFail();
        return view('employer.exams.show', [
            'exam' => $exam,
            'id'     => $exam->course_id,
            'subscriber_id'    => $subscriber_id,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @param  int  $level_id
     * @return \Illuminate\Http\Response
     */
    public function create($course_id,$subscriber_id)
    {
        if(!userSubscriber($subscriber_id)){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        $levels = Level::where('course_id',$course_id)->where('subscriber_id',$subscriber_id)->get();
        $users = CourseUser::where('course_id',$course_id)->with('user')->where('type','3')->get();
        $groups = StudentGroup::where('subscriber_id',$subscriber_id)->where('course_id',$course_id)->get();
        return view('employer.exams.create', [
            'id'     => $course_id,
            'users'  => $users,
            'groups' => $groups,
            'levels' => $levels,
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
       $implode = implode(',',Level::where('course_id',$course_id)->where('subscriber_id',$subscriber_id)->get()->pluck('id')->toArray());
       if($request->level_id){
           $question = Bank::where('level_id',$request->level_id)->where('course_id',$course_id)->where('subscriber_id',$subscriber_id)->count();
       }else{
           $question = Bank::where('course_id',$course_id)->where('subscriber_id',$subscriber_id)->count();
       }
       //Make Validation
       $this->rules['title'] = 'required|max:200';
       $this->rules['level_id'] = 'in:'.$implode.',';
       $this->rules['description'] = 'sometimes|nullable';
       $this->rules['start_date'] = 'required|date|after_or_equal:today';
       $this->rules['end_date'] = 'required|date|after_or_equal:start_date';
       $this->rules['time_minutes'] = 'required|integer';
       $this->rules['success_average'] = 'required|integer|between:0,100';
       $this->rules['try_no'] = 'required|integer';
       $this->rules['question_no'] = 'required|integer|between:0,'.$question;
       // $this->rules['success_message'] = 'required';
       // $this->rules['fail_message'] = 'required';
       $this->rules['question_choose'] = 'required|in:0,1';
       $this->rules['user_id'] = 'sometimes|nullable|array|in:'.implode(',',User::where('type','3')->where('subscriber_id',$subscriber_id)->get()->pluck('id')->toArray());
       $this->rules['group_id'] = 'sometimes|nullable|array|exists:student_groups,id';
       $data = $this->validate($request, $this->rules);
       // Fixed Data
       $data['user_id'] = User::where('type','1')->where('subscriber_id',$subscriber_id)->first()->id;
       $data['course_id'] = $course_id;
       $data['subscriber_id'] = $subscriber_id;
       if($request->start_date){
           $data['start_date'] = date('Y-m-d',strtotime($request->start_date));
       }
       if($request->end_date){
           $data['end_date'] = date('Y-m-d',strtotime($request->end_date));
       }
       $exam = Exam::create($data);
       //Make Questions From Questions Bank
       if($request->level_id){
           if ($request->question_choose == 1) {
               $banks = Bank::where('level_id',$request->level_id)->where('course_id',$course_id)->where('subscriber_id',$subscriber_id)->get()->random($request->question_no);
           }else{
                $banks = Bank::where('level_id',$request->level_id)->where('course_id',$course_id)->where('subscriber_id',$subscriber_id)->limit($request->question_no)->get();
           }
       }else{
           if ($request->question_choose == 1) {
               $banks = Bank::where('course_id',$course_id)->where('subscriber_id',$subscriber_id)->get()->random($request->question_no);
           }else{
                $banks = Bank::where('course_id',$course_id)->where('subscriber_id',$subscriber_id)->limit($request->question_no)->get();
           }
       }
       foreach ($banks as $key => $bank) {
           ExamBank::UpdateOrCreate(
               ['exam_id' => $exam->id,
                'bank_id' => $bank->id]
          );
       }
       //If select students
       if($request->type == '1'){
       //Assign Users To Exam
       if(!empty($request->group_id)){
           foreach ($request->group_id as $key => $group) {
               $user_group = GroupUser::where('student_group_id',$group)->get();
               foreach ($user_group as $key => $user) {
                   ExamUser::UpdateOrCreate(
                       ['exam_id'  => $exam->id,
                        'user_id'     => $user->user_id]
                  );
               }
           }
       }
       if(!empty($request->user_id)){
           foreach ($request->user_id as $key => $user) {
               ExamUser::UpdateOrCreate(
                   ['exam_id'  => $exam->id,
                    'user_id'     => $user]
              );
           }
       }
       //IF Assign To ALl Students
       }else{
           $users = CourseUser::where('course_id',$course_id)->with('user')->where('type','3')->get();
           if(!empty($users)){
               foreach ($users as $key => $user) {
                   ExamUser::UpdateOrCreate(
                       ['exam_id'  => $exam->id,
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
        $exam = Exam::where('id',$id)->where('subscriber_id',$subscriber_id)->with('examUser:exam_id,user_id')->firstOrFail();
        $choose = [];
        $choose_users = $exam->examUser;
        foreach ($exam->examUser as $user) {
            $choose[] = $user->user_id;
        }
        $levels = Level::where('course_id',$exam->course_id)->where('subscriber_id',$subscriber_id)->get();
        $users = CourseUser::where('course_id',$exam->course_id)->with('user')->where('type','3')->get();
        $groups = StudentGroup::where('subscriber_id',$subscriber_id)->where('course_id',$exam->course_id)->get();
        return view('employer.exams.edit', [
            'title' => 'title',
            'edit' => $exam,
            'users'  => $users,
            'groups' => $groups,
            'choose' => $choose,
            'levels' => $levels,
            'id'     => $exam->course_id,
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
        $exam = Exam::where('id',$id)->where('subscriber_id',$subscriber_id)->firstOrFail();
        $implode = implode(',',Level::where('course_id',$exam->course_id)->where('subscriber_id',$subscriber_id)->get()->pluck('id')->toArray());
        if($request->level_id){
            $question = Bank::where('level_id',$request->level_id)->where('course_id',$exam->course_id)->where('subscriber_id',$subscriber_id)->count();
        }else{
            $question = Bank::where('course_id',$exam->course_id)->where('subscriber_id',$subscriber_id)->count();
        }
        //Make Validation
        $this->rules['title'] = 'required|max:200';
        $this->rules['level_id'] = 'in:'.$implode.',';
        $this->rules['description'] = 'sometimes|nullable';
        $this->rules['start_date'] = 'required|date|after_or_equal:today';
        $this->rules['end_date'] = 'required|date|after_or_equal:start_date';
        $this->rules['time_minutes'] = 'required|integer';
        $this->rules['success_average'] = 'required|integer|between:0,100';
        $this->rules['try_no'] = 'required|integer';
        $this->rules['question_no'] = 'required|integer|between:0,'.$question;
        // $this->rules['success_message'] = 'required';
        // $this->rules['fail_message'] = 'required';
        $this->rules['question_choose'] = 'required|in:0,1';
        $this->rules['user_id'] = 'sometimes|nullable|array|in:'.implode(',',User::where('type','3')->where('subscriber_id',$subscriber_id)->get()->pluck('id')->toArray());
        $this->rules['group_id'] = 'sometimes|nullable|array|exists:student_groups,id';
        $data = $this->validate($request, $this->rules);
        //Update Exam
        // Fixed Data
        $data['user_id'] = User::where('type','1')->where('subscriber_id',$subscriber_id)->first()->id;
        $data['course_id'] = $exam->course_id;
        $data['subscriber_id'] = $subscriber_id;
        if($request->start_date){
            $data['start_date'] = date('Y-m-d',strtotime($request->start_date));
        }
        if($request->end_date){
            $data['end_date'] = date('Y-m-d',strtotime($request->end_date));
        }
        //Update Data
        $exam->update($data);
        ExamUser::where('exam_id',$id)->delete();
        //Make Questions From Questions Bank
        ExamBank::where('exam_id',$id)->delete();
        if($request->level_id){
            if ($request->question_choose == 1) {
                $banks = Bank::where('level_id',$request->level_id)->where('course_id',$exam->course_id)->where('subscriber_id',$subscriber_id)->get()->random($request->question_no);
            }else{
                 $banks = Bank::where('level_id',$request->level_id)->where('course_id',$exam->course_id)->where('subscriber_id',$subscriber_id)->limit($request->question_no)->get();
            }
        }else{
            if ($request->question_choose == 1) {
                $banks = Bank::where('course_id',$exam->course_id)->where('subscriber_id',$subscriber_id)->get()->random($request->question_no);
            }else{
                 $banks = Bank::where('course_id',$exam->course_id)->where('subscriber_id',$subscriber_id)->limit($request->question_no)->get();
            }
        }
        foreach ($banks as $key => $bank) {
            ExamBank::UpdateOrCreate(
                ['exam_id' => $exam->id,
                 'bank_id' => $bank->id]
           );
        }
        //Assign Users To Exam
        if(!empty($request->group_id)){
            foreach ($request->group_id as $key => $group) {
                $user_group = GroupUser::where('student_group_id',$group)->get();
                foreach ($user_group as $key => $user) {
                    ExamUser::UpdateOrCreate(
                        ['exam_id'  => $exam->id,
                         'user_id'     => $user->user_id]
                   );
                }
            }
        }
        if(!empty($request->user_id)){
            foreach ($request->user_id as $key => $user) {
                ExamUser::UpdateOrCreate(
                    ['exam_id'  => $exam->id,
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
        $exam = Exam::where('id',$id)->where('subscriber_id',$subscriber_id)->firstOrFail();
        $exam->delete();
    }



}
