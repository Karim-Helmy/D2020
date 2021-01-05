<?php
namespace App\Http\Controllers\Employer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Hash;
use App\Imports\UsersImport;
use App\PackageOption;
use App\Course;
use App\CourseUser;
use App\Subscriber;
use App\GroupUser;
use App\ExcelUser;
use App\StudentGroup;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Mail;
use App\Mail\StudentRegistration;
use App\Jobs\SendMail;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        if(!userSubscriber($id)){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        $user = User::where('subscriber_id',$id)->where('type','!=','1');
        if(request()->filled('name')){
            $user->where('name','like','%'.request()->name.'%');
        }
        if(request()->filled('type')){
            $user->where('type',request()->type);
        }
        $users = $user->get();
        return view('employer.users.index', [
            'title' => trans("admin.show"),
            'index' => $users,
            'id' => $id,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function father(Request $request,$id)
    {
        if(!userSubscriber($id)){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        $user = User::where('mobile',request()->mobile)->where('subscriber_id',$id)->where('type','4')->first();
       return $user;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        if(!userSubscriber($id)){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        $courses = Course::where([
            ['status','1'],
            ['subscriber_id',$id],
        ])->get();
        $fathers = User::where([
            ['status','1'],
            ['type','4'],
            ['subscriber_id',$id],
        ])->get();
        return view('employer.users.create', [
            'title' => trans("admin.add user"),
            'courses'  => $courses,
            'fathers'  => $fathers,
            'id'       => $id,
        ]);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request,$id)
   {
       // Start Validation Of Number of students In package
       $package = Subscriber::where('id',$id)->first()->package_id;
       $count_package = PackageOption::where('package_id',$package)->where('option_id','1')->first()->value ?? "1";
       $count_user = User::where('type','3')->where('subscriber_id',$id)->count();
       if($request->type == "3" && $count_user >= $count_package){
           return redirect()->back()->with([
          'error' => trans('لقد وصلت للحد الأقصى من عدد الطلاب ... للحصول على عدد اكبر من الطلاب يرجى  تغيير الباقة'),
          ]);
       }
       // End Package Validation for count od students
       $subscriber_id = $id;
       //Make Validation
       $this->rules['name'] = 'required|max:250';
       $this->rules['username'] = 'required|max:250|unique:users,username';
       $this->rules['email'] = 'required|email|max:50';
       $this->rules['id_number'] = 'required|min:10|max:17|unique:users,id_number';
       $this->rules['mobile'] = 'required|unique:users,mobile|regex:/(05)[0-9]{8}/|size:10';
       $this->rules['address'] = 'sometimes|nullable|max:200';
       $this->rules['nationality'] = 'sometimes|nullable|max:200';
       $this->rules['status'] = 'sometimes|nullable|in:0,1';
       $this->rules['type'] = 'required|in:2,3,4';
       $this->rules['password'] = 'required|min:6|confirmed';
       $this->rules['image'] = 'sometimes|nullable|image';
       $this->rules['birth_date'] = 'sometimes|nullable|date|before:today|after:1930-01-01';
       $this->rules['course_id'] = 'sometimes|nullable|exists:courses,id';
       $data = $this->validate($request, $this->rules);
       // Hash password
       $data['password'] = Hash::make($request->password);
       // subscriber_id
       $data['subscriber_id'] = $subscriber_id;
       // Upload Image
       if ($request->hasFile('image')) {
          $destination = "uploads/" . $subscriber_id . "/profile/" . date("Y") . "/" . date("m") . "/";
          $data['photo'] = UploadImages($destination, $request->file('image')); // Upload Image
      }
      if($request->birth_date){
          $data['birth_date'] = date('Y-m-d',strtotime($request->birth_date));
      }
      if ($request->father_mobile) {
          // To delete First Name From Student
          $words = explode( ' ', $request->name);
          array_shift( $words);
          $father_name = implode( ' ', $words);
          // If father mobile found update else Create
          $father = User::updateOrCreate(
              ['mobile' => $request->father_mobile],
              [
                  'username'      => $request->father_mobile,
                  'name'          => $father_name ?? 'a' ,
                  'password'      => Hash::make($request->father_mobile) ,
                  'id_number'     => $request->father_id ,
                  'status'        => '1' ,
                  'type'          => '4' ,
                  'subscriber_id' => $id
              ]
          );
          $data['father_id'] = $father->id;
      }
       //Create User And Success Message
       $user = User::create($data);
       //Send Email [Account Data] To User
       $user['pass'] = $request->password;
       Mail::to($request->email)->send(new StudentRegistration($user));
       // if user is not father .. insert student an trainer in this course
       if($user->type != 4){
           if($request->course_id){
               CourseUser::create([
                   'course_id' => $request->course_id,
                   'user_id'   => $user->id,
                   'type'      => $user->type,
               ]);
           }
       }
       session()->flash('success', trans("admin.add Successfully"));
       return redirect()->back();
   }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($subscriber_id,$id)
    {
        if(!userSubscriber($subscriber_id)){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        $users = User::where('subscriber_id',$subscriber_id)->where('type','!=','1')->where('id',$id)->firstOrFail();
        $fathers = User::where([
            ['status','1'],
            ['type','4'],
            ['subscriber_id',$subscriber_id],
        ])->get();
        return view('employer.users.edit', [
            'title'     => trans('admin.edit'),
            'edit'     => $users,
            'fathers'  => $fathers,
            'id'       => $id,
            'subscriber_id'     => $subscriber_id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function update(Request $request,$subscriber_id,$id)
   {
       $users = User::where('subscriber_id',$subscriber_id)->where('type','!=','1')->where('id',$id)->firstOrFail();
       $subscriber_id = $subscriber_id;
       $fathers = User::where([
           ['status','1'],
           ['type','4'],
           ['subscriber_id',$subscriber_id],
       ])->get();
       $implode = implode(',',$fathers->pluck("id")->toArray());
       //Make Validation
       $this->rules['name'] = 'required|max:250';
       $this->rules['username'] = 'required|max:250|unique:users,username,'.$id;
       $this->rules['email'] = 'required|email|max:50';
       $this->rules['id_number'] = 'required|min:10|max:17|unique:users,id_number,'.$id;
       $this->rules['mobile'] = 'required|unique:users,mobile|regex:/(05)[0-9]{8}/|size:10';
       $this->rules['address'] = 'sometimes|nullable|max:200';
       $this->rules['nationality'] = 'sometimes|nullable|max:200';
       $this->rules['status'] = 'sometimes|nullable|in:0,1';
       $this->rules['type'] = 'required|in:2,3,4';
       $this->rules['password'] = 'sometimes|nullable|min:6|confirmed';
       $this->rules['image'] = 'sometimes|nullable|image';
       $this->rules['birth_date'] = 'sometimes|nullable|date|before:today|after:1930-01-01';
       $this->rules['father_id'] = 'sometimes|nullable|in:'.$implode;
       $data = $this->validate($request, $this->rules);
       // Hash password
       if($request->password){
           $data['password'] = Hash::make($request->password);
       }else{
           $data['password'] = $users->password;
       }
       if($request->birth_date){
           $data['birth_date'] = date('Y-m-d',strtotime($request->birth_date));
       }
       // subscriber_id
       $data['subscriber_id'] = $subscriber_id;
       // Upload Image
       if ($request->hasFile('image')) {
           if (file_exists(public_path('uploads/' . $users->photo))) {
               @unlink(public_path('uploads/' . $users->photo));
           }
          $destination = "uploads/" . $subscriber_id . "/profile/" . date("Y") . "/" . date("m") . "/";
          $data['photo'] = UploadImages($destination, $request->file('image')); // Upload Image
      }
       //Create User And Success Message
       $users->update($data);

       session()->flash('success', trans("admin.add Successfully"));
       return redirect()->back();
   }


    /**
     * Show  Upload Excel File
     *
     * @return \Illuminate\Http\Response
     */
    public function excel($subscriber_id)
    {
        $courses = Course::where([
            ['status','1'],
            ['subscriber_id',$subscriber_id],
        ])->get();
        return view('employer.users.excel', [
            'courses'  => $courses,
            'id'  => $subscriber_id,
        ]);
    }

    /**
     * [import Students Excel File]
     * 0 => name , 1 => username , 2 => Password , 3 => type (in:2,3,4) (2 => Trainer , 3 => Student , 4 => Father)
     * @param  Request $request [File Excel]
     * @return [type]           [Success Message And Return Back]
     */
    public function import(Request $request,$subscriber_id)
   {
       // Make Validation
        $this->rules['excel'] = 'required|mimes:xlsx,xls,csv';
        $data = $this->validate($request, $this->rules);
        //Return inserted data by excel file
        $excel = Excel::toArray(new UsersImport, $request->file('excel'));
        ExcelUser::where('user_id',auth()->guard('webAdmin')->id().'9999')->delete();
        foreach (array_slice($excel[0],1) as $excel) {
            ExcelUser::create(
                [
                    'name'      => $excel[0],
                    'username'  => $excel[1],
                    'password'  => $excel[2],
                    'email'     => $excel[3],
                    'mobile'    => $excel[4],
                    'id_number' => $excel[5],
                    'type'      => $excel[6],
                    'user_id' => auth()->guard('webAdmin')->id().'9999'
                ]
            );
            // Success Message And Return Back
        }
         return redirect('/employer/users/import/get/'.$subscriber_id);
   }

   public function importGet($subscriber_id)
  {
      $excel_user = ExcelUser::where('user_id',auth()->guard('webAdmin')->id().'9999')->get();
       return view('employer.users.import', [
           'excel_rows'  => $excel_user,
           'id'  => $subscriber_id,
       ]);
  }

   /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function importStore(Request $request,$subscriber_id)
  {
      // Start Validation Of Number of students In package
      $package = Subscriber::where('id',$subscriber_id)->first()->package_id;
      $count_package = PackageOption::where('package_id',$package)->where('option_id','1')->first()->value ?? "1"; //10
      $count_user = User::where('type','3')->where('subscriber_id',$subscriber_id)->count();  //9
      $diff = $count_package - $count_user; //1
      $count = collect(request()->type)->filter(function ($value, $key) {
          return $value == 3;
      });
      $excel_count = $count->count(); //1
      if($excel_count > $diff){
          return redirect()->back()->with([
         'error' => trans('لقد وصلت للحد الأقصى من عدد الطلاب ... للحصول على عدد اكبر من الطلاب يرجى  تغيير الباقة'),
         ]);
      }
      // Delete Temporary
      ExcelUser::where('user_id',auth()->guard('webAdmin')->id().'9999')->delete();
      foreach ($request->name as $key => $value) {
          ExcelUser::create([
              'name'          => $value,
              'username'      => $request->username[$key],
              'password'      => $request->password[$key],
              'email'         => $request->email[$key],
              'id_number'     => $request->id_number[$key],
              'mobile'        => $request->mobile[$key],
              'type'          => $request->type[$key],
              'user_id'       => auth()->guard('webAdmin')->id().'9999'
          ]);
      }
      // End Validation Of Number of students In package
      $subscriber_id = $subscriber_id;
      //Make Validation
      $this->rules['name.*'] = 'required|max:200';
      $this->rules['username.*'] = 'required|max:250|unique:users,username|max:50';
      $this->rules['email.*'] = 'required|email|max:50';
      $this->rules['id_number.*'] = 'required|min:10|max:17|unique:users,id_number';
      $this->rules['mobile.*'] = 'required|unique:users,mobile|regex:/(05)[0-9]{8}/|size:10';
      $this->rules['type.*'] = 'required|in:2,3,4';
      $this->rules['password.*'] = 'required|min:6';
      $data = $this->validate($request, $this->rules);
      $i = 0;
      foreach ($request->name as $key => $value) {
          $i+5;
          //Create User And Success Message
          $user = User::create([
              'name'          => $value,
              'username'      => $request->username[$key],
              'password'      => Hash::make($request->password[$key]),
              'email'         => $request->email[$key],
              'id_number'     => $request->id_number[$key],
              'mobile'        => $request->mobile[$key],
              'type'           => $request->type[$key],
              'subscriber_id' => $subscriber_id,
              'status'        => '1',
          ]);
          //Send Email [Account Data] To User
          $email_array = [ 'username'=>$request->username[$key],'email'=>$request->email[$key],'password'=>$request->password[$key]];
          //Mail::to($request->email[$key])->send(new StudentRegistration($user));
          SendMail::dispatch($email_array)->delay(now()->addSeconds($i));
      }
      ExcelUser::where('user_id',auth()->guard('webAdmin')->id().'9999')->delete();
      session()->flash('success', trans("admin.add Successfully"));
      return redirect('employer/users/'.$subscriber_id);
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
            $user = User::where('subscriber_id',$subscriber_id)->where('type','!=','1')->where('id',$id)->firstOrFail();
            $user->delete();
        }


}
