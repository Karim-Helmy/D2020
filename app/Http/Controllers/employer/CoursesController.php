<?php

namespace App\Http\Controllers\Employer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PackageOption;
use App\PackageCategory;
use App\Course;
use App\Level;
use App\Category;
use App\Subscriber;
use App\User;
use App\CourseUser;
use App\Rules\ValidateYoutube;

class CoursesController extends Controller
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
        $course = Course::where('subscriber_id',$id)->with('category')->get();
        return view('employer.courses.index', [
            'title' => trans('admin.courses'),
            'index' => $course,
            'id' => $id,
        ]);
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
        // Start Validation Of Number of students In package
        $package = Subscriber::where('id',$id)->first()->package_id;
        $categories = PackageCategory::with('category')->where('package_id',$package)->get();
        return view('employer.courses.create', [
            'title' => trans("admin.add course"),
            'categories' => $categories,
            'id' => $id,
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
        if(!userSubscriber($id)){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        //Category Strong Validation
        $package = Subscriber::where('id',$id)->first()->package_id;
        $categories = PackageCategory::where('package_id',$package)->get();
        $implode_categories = implode(',',$categories->pluck("category_id")->toArray());
        // Make Validation
        $this->rules['title'] = 'required|max:200';
        $this->rules['start_date'] = 'required|date|after_or_equal:today';
        $this->rules['end_date'] = 'required|date|after:start_date';
        $this->rules['days_no'] = 'required|numeric';
        $this->rules['hours_no'] = 'required|numeric';
        $this->rules['description'] = 'sometimes|nullable';
        $this->rules['active'] = 'required|in:0,1';
        $this->rules['repeater'] = 'required';
        //$this->rules['level_number'] = 'required|array|numeric';
        $this->rules['image'] = 'required|image';
        $this->rules['video'] = ['sometimes','nullable','url',new ValidateYoutube];
        $this->rules['object'] = 'required';
        $this->rules['category_id'] = 'required|in:'.$implode_categories;

        $data = $this->validate($request, $this->rules);
        $add = new Course;
        // subscriber_id
        $add->subscriber_id = $id;
        // Upload Image
        if ($request->hasFile('image')) {
            $destination = "uploads/" . $id . "/courses/" . date("Y") . "/" . date("m") . "/";
            $add->logo = UploadImages($destination, $request->file('image')); // Upload Image
        }
        if($request->start_date){
            $add->start_date = date('Y-m-d',strtotime($request->start_date));
        }
        if($request->end_date){
            $add->end_date = date('Y-m-d',strtotime($request->end_date));
        }
        $add->title = $request->title;
        $add->days_no = $request->days_no;
        $add->hours_no = $request->hours_no;
        $add->description = $request->description;
        $add->status = $request->active;
        $add->category_id = $request->category_id;
        $add->object = $request->object;
        $add->video = $request->video;
        $add->save();
        //add levels
        $lastid  = $add->id;
        $levels = $request->repeater;
        foreach ($levels as $level)
        {
            if ($level['level_name'] !== null && $level['level_number'] !== null)
            {
                $addlevels= new Level();
                $addlevels->subscriber_id  =  $id;
                $addlevels->course_id  =  $lastid;
                $addlevels->title = $level['level_name'];
                $addlevels->ordering = $level['level_number'];
                $addlevels->save();
           }
        }

        session()->flash('success', trans("admin.add Successfully"));
        return back();
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
        $course = Course::where('subscriber_id',$subscriber_id)->with('level')->where('id',$id)->firstOrFail();
        $package = Subscriber::where('id',$subscriber_id)->first()->package_id;
        $categories = PackageCategory::with('category')->where('package_id',$package)->get();
        return view('employer.courses.edit', [
            'title' => trans("admin.edit") . ' : ' . $course->title,
            'edit'  => $course,
            'categories' => $categories,
            'id'       => $id,
            'subscriber_id'     => $subscriber_id,

        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($subscriber_id,$id)
    {
        if(!userSubscriber($subscriber_id)){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        $course = Course::where('subscriber_id',$subscriber_id)->with('level','courseTrainer.user')->withCount('courseStudent','video','scorm','photo','attachment')->where('id',$id)->firstOrFail();
        return view('employer.courses.show', [
            'title' => trans("admin.show") . ' : ' . $course->title,
            'course'  => $course,
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
        $subscriber_id = $subscriber_id;
        //Category Strong Validation
        $package = Subscriber::where('id',$subscriber_id)->first()->package_id;
        $categories = PackageCategory::where('package_id',$package)->get();
        $implode_categories = implode(',',$categories->pluck("category_id")->toArray());
        // Make Validation
        $this->rules['title'] = 'required|max:200';
        $this->rules['start_date'] = 'required|date';
        $this->rules['end_date'] = 'required|date|after:start_date';
        $this->rules['days_no'] = 'required|numeric';
        $this->rules['hours_no'] = 'required|numeric';
        $this->rules['description'] = 'sometimes|nullable';
        $this->rules['category_id'] = 'required|in:'.$implode_categories;
        $this->rules['active'] = 'required|in:0,1';
        $this->rules['video'] = ['sometimes','nullable','url',new ValidateYoutube];
        $this->rules['object'] = 'required';
        $this->rules['image'] = 'sometimes|nullable|image';
        $this->rules['repeater'] = 'required';
        $data = $this->validate($request, $this->rules);
        $edit = Course::where('subscriber_id',$subscriber_id)->where('id',$id)->firstOrFail();
        // subscriber_id
        $edit->subscriber_id = $subscriber_id;
        // Upload Image
        if ($request->hasFile('image')) {
            if (file_exists(public_path('uploads/' . $edit->logo))) {
                @unlink(public_path('uploads/' . $edit->logo));
            }
            $destination = "uploads/" . $subscriber_id . "/courses/" . date("Y") . "/" . date("m") . "/";
            $edit->logo = UploadImages($destination, $request->file('image')); // Upload Image
        }
        if($request->start_date){
            $edit->start_date = date('Y-m-d',strtotime($request->start_date));
        }
        if($request->end_date){
            $edit->end_date = date('Y-m-d',strtotime($request->end_date));
        }
        $edit->title = $request->title;
        $edit->object = $request->object;
        $edit->video = $request->video;
        $edit->days_no = $request->days_no;
        $edit->hours_no = $request->hours_no;
        $edit->description = $request->description;
        $edit->category_id = $request->category_id;
        $edit->status = $request->active;

        $edit->save();
        //add levels
        $levels = $request->repeater;
        $ids = [];
        foreach ($levels as $key => $value) {
            $ids[] = $value['level_id'];
        }
            Level::where('course_id',$id)->where('subscriber_id',$subscriber_id)
                    ->whereNotIn('id', $ids)->delete();
            foreach ($levels as $key => $level)
            {
                    Level::updateOrCreate(
                        [
                            'subscriber_id' => $subscriber_id,
                            'course_id'     => $id,
                            'id'            => array_key_exists($key, $ids) ? $ids[$key] : null,
                        ],
                        [
                            'title'         => $level['level_name'],
                            'ordering'      => $level['level_number']
                        ]
                    );
            }

        session()->flash('success', trans("admin.add Successfully"));
        return back();
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
         $user = Course::where('subscriber_id',$subscriber_id)->where('id',$id)->firstOrFail();
         if (file_exists(public_path('uploads/' . $user->logo))) {
             @unlink(public_path('uploads/' . $user->logo));
         }
         $user->delete();
     }


}
