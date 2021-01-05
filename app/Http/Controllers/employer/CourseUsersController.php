<?php

namespace App\Http\Controllers\Employer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Course;
use App\CourseUser;
use App\Imports\CoursesImport;
use Maatwebsite\Excel\Facades\Excel;

class CourseUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($course_id,$id)
    {
        if(!userSubscriber($id)){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        $users = CourseUser::with('user')->whereHas('course',function($query) use($id){
            $query->where('subscriber_id',$id);
        })->where('course_id',$course_id)->get();
        return view('employer.assign.index', [
            'users' => $users,
            'title' => trans('admin.user courses'),
            'id'    => $course_id,
            'subscriber_id'    => $id,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($course_id,$id)
    {
        if(!userSubscriber($id)){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        $course_users = CourseUser::where('course_id',$course_id)->get()->pluck("user_id")->toArray();
        $users = User::where('status','1')->where('subscriber_id',$id)
        ->whereNotIn('id',$course_users)
        ->whereIn('type', [2, 3])->get();
        return view('employer.assign.create', [
            'users' => $users,
            'id'    => $course_id,
            'subscriber_id'    => $id,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function autocompleteCourse(Request $request,$id)
    {
        $data = User::select("name")
                ->where("name","LIKE","%{$request->input('name')}%")
                ->whereIn('type',['2','3'])
                ->where('subscriber_id',$id)
                ->get();

        return response()->json($data);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request,$course_id,$id)
   {
       if(!userSubscriber($id)){
           return redirect('admin/index')->with([
               'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
           ]);
       }
       Course::where('id',$course_id)->where('subscriber_id',$id)->firstOrFail();
       // Make Validation
       $users = User::where('status','1')->where('subscriber_id',$id)->whereIn('type', [2, 3])->get();
        $implode_name = implode(',',$users->pluck("name")->toArray());
       //$this->rules['type'] = 'required|in:2,3';
       if($request->type == '1'){
           $this->rules['user_id'] = 'required|in:'.$implode_name;
       }else{
           $this->rules['user_id'] = 'required|array';
       }
       $data = $this->validate($request, $this->rules);
       //Create User And Success Message
       if($request->type == '1'){
           $check_trainer = User::where('name','like',$request->user_id)->where('subscriber_id',$id)->firstOrFail();
           $user = CourseUser::updateOrCreate(
               ['course_id' => $course_id, 'user_id' => $check_trainer->id],
               ['type' =>$check_trainer->type]
           );
       }else{
           foreach ($request->user_id as $userID) {
               $check = User::where('id',$userID)->where('subscriber_id',$id)->whereIn('type',['2','3'])->firstOrFail();
               CourseUser::updateOrCreate(
                   ['course_id' => $course_id, 'user_id' => $userID],
                   ['type' =>$check->type]
               );
           }
       }

       session()->flash('success', trans("admin.add Successfully"));
       return redirect()->back();
   }


   //  /**
   //   * Show  Upload Excel File
   //   *
   //   * @return \Illuminate\Http\Response
   //   */
   //  public function excel($id,$subscriber_id)
   //  {
   //      if(!userSubscriber($subscriber_id)){
   //          return redirect('admin/index')->with([
   //              'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
   //          ]);
   //      }
   //      return view('employer.assign.excel',['id' => $id,'subscriber_id' => $subscriber_id]);
   //  }
   //
   //  /**
   //   * [import Students Excel File]
   //   * 0 => name , 1 => username , 2 => Password , 3 => type (in:2,3,4) (2 => Trainer , 3 => Student , 4 => Father)
   //   * @param  Request $request [File Excel]
   //   * @return [type]           [Success Message And Return Back]
   //   */
   //  public function import(Request $request,$id,$subscriber_id)
   // {
   //     if(!userSubscriber($subscriber_id)){
   //         return redirect('admin/index')->with([
   //             'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
   //         ]);
   //     }
   //     // Make Validation
   //      $this->rules['excel'] = 'required|mimes:xlsx,xls,csv';
   //      $data = $this->validate($request, $this->rules);
   //      //Return inserted data by excel file
   //      $excel = Excel::toArray(new CoursesImport, $request->file('excel'));
   //
   //      // foreach all users in excel file
   //      foreach ($excel[0] as $key => $row) {
   //          $id_number = $row[0];
   //          $user = User::where('id_number',$id_number)->where('subscriber_id',$subscriber_id)->first();
   //          if($user){
   //              // if user is student -> insert in this Course
   //              CourseUser::updateOrCreate(
   //                  ['course_id' => $id,
   //                  'user_id'   => $user->id],
   //                  ['type'      => $user->type]
   //              );
   //          }
   //
   //      }
   //      // Success Message And Return Back
   //      session()->flash('success', trans("admin.add Successfully"));
   //      return  redirect()->back();
   //
   // }

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
        $users = CourseUser::where('id',$id)->firstOrFail();
        CourseUser::where('course_id',$users->course_id)->whereHas('course',function($query) use($subscriber_id){
            $query->where('subscriber_id',$subscriber_id);
        })->firstOrFail();
        $users->delete();
    }



}
