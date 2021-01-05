<?php

namespace App\Http\Controllers\Employer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ExamAttempt;
use App\Level;
use App\CourseUser;
use App\Bank;
use App\User;

class BanksController extends Controller
{
    /**
     * Display a listing of the resource.
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
        // Check Permission
        $banks = Bank::with('level')->where('course_id',$course_id)->where('subscriber_id',$subscriber_id)->get();
        //Get Videos
        return view('employer.banks.index', [
            'title'    => trans('admin.banks'),
            'banks' => $banks,
            'id' => $course_id,
            'subscriber_id'    => $subscriber_id,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @param  int  $course_id
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
        return view('employer.banks.create', [
            'id'    => $course_id,
            'levels'=> $levels,
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
       //Make Validation
       $this->rules['title'] = 'required|max:200';
       $this->rules['description'] = 'sometimes|nullable';
       $this->rules['grade'] = 'required|integer';
       $this->rules['ordering.*'] = 'required_with:answers.*';
       $this->rules['level_id'] = 'required|in:'.implode(',',Level::where('course_id',$course_id)->where('subscriber_id',$subscriber_id)->get()->pluck('id')->toArray());
       $data = $this->validate($request, $this->rules);
       //Create Video
       $data['course_id'] = $course_id;
       $data['user_id'] = User::where('type','1')->where('subscriber_id',$subscriber_id)->first()->id;
       $data['subscriber_id'] = $subscriber_id;
       if($request->type == "tf"){
         $data['best_answer'] = $request->best_answer1;
         $data['answers'] = 'صح|خطأ';
       }else{
           $data['best_answer'] = $request->best_answer2;
           $values = collect($request->answers)->push($request->best_answer2);
           $keys   = collect($request->ordering)->push($request->ordering2);
           $answer = array_combine(collect($keys)->toArray(), collect($values)->toArray()); // Add Keys To Values
           $sort = collect($answer)->sortKeys();
           $data['answers'] = $sort->implode('|');
       }
       $bank = Bank::create($data);
       session()->flash('success', trans("admin.add Successfully"));
       return redirect()->back();
   }

       /**
        * Show the form for editing the specified resource.
        *
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
           $banks = Bank::where('subscriber_id',$subscriber_id)->where('id',$id)->firstOrFail();
           $levels = Level::where('course_id',$banks->course_id)->get();
           return view('employer.banks.edit', [
               'title' => 'edit',
               'edit'  => $banks,
               'levels'=> $levels,
               'subscriber_id'    => $subscriber_id,
               'id' =>$banks->course_id,
           ]);
       }

       /**
        * Update the specified resource in storage.
        *
        * @param  \Illuminate\Http\Request  $request
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
       public function update(Request $request, $id,$subscriber_id)
       {
           if(!userSubscriber($subscriber_id)){
               return redirect('admin/index')->with([
                   'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
               ]);
           }
           //Check Permissions
           $banks = Bank::where('subscriber_id',$subscriber_id)->where('id',$id)->firstOrFail();
           // Make Validation
           $this->rules['title'] = 'required|max:200';
           $this->rules['description'] = 'sometimes|nullable';
           $this->rules['grade'] = 'required|integer';
           //$this->rules['best_answer'] = 'required';
           $this->rules['level_id'] = 'required|in:'.implode(',',Level::where('course_id',$banks->course_id)->where('subscriber_id',$subscriber_id)->get()->pluck('id')->toArray());
           $data = $this->validate($request, $this->rules);
           //Update Data
           $data['course_id'] = $banks->course_id;
           $data['user_id'] = User::where('type','1')->where('subscriber_id',$subscriber_id)->first()->id;
           $data['subscriber_id'] = $subscriber_id;
           if($request->best_answer1){
               $data['answers'] = $banks->answers;
               $data['best_answer'] = $request->best_answer1;
           }else{
               $answer = collect($request->answers)->push($request->best_answer);
               $data['answers'] = $answer->implode('|');
               $data['best_answer'] = $request->best_answer;
           }
           $banks->update($data);
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
            //Find And Delete
            $bank = Bank::where('id',$id)->where('subscriber_id',$subscriber_id)->firstOrFail();
            $bank_used = ExamAttempt::where('bank_id',$id)->first();
            if(!$bank_used){
                $bank->delete();
            }
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
            $bank = Bank::where('id',$id)->where('subscriber_id',$subscriber_id)->firstOrFail();
            return view('employer.banks.show', [
                'bank' => $bank,
                'subscriber_id'    => $subscriber_id,
            ]);
        }



}
