<?php

namespace App\Http\Controllers\Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PhotoCourse;
use App\Photo;
use App\Level;
use App\StudentView;
use App\CourseUser;
use App\Stage;

class PhotosController extends Controller
{
    /**
    * Display a listing of the resource.
    * @param  int  $level_id
    * @return \Illuminate\Http\Response
    */
    public function index($level_id)
    {
        // Check Permission
        $check = Level::where('id',$level_id)->whereHas('course.courseUser',function($query){
            $query->where('user_id',auth()->id());
        })->with(['course.courseUser' => function($query){
            $query->where('user_id',auth()->id());
        }])->whereHas('course',function($query){
            $query->whereDate('courses.start_date','<',date('Y-m-d'));
        })->firstOrFail();
        //Check Views
        $views = StudentView::firstOrCreate(
            ['type' => 'photo', 'type_id' => $level_id, 'view_date' => date('Y-m-d'), 'user_id' => auth()->id()]
        );
        if($views->wasRecentlyCreated){
            $course = CourseUser::where('course_id',$check->course_id)->where('user_id',auth()->id())->first();
            $course->increment('count_photo');
        }
        //Get Photos
        $photos = PhotoCourse::where('level_id',$level_id)->where('subscriber_id',auth()->user()->subscriber_id)->with('photo.user')->orderBy('id','desc')->get();
        return view('student.photos.index', [
            'photos' => $photos,
            'id' => $level_id,
            'check' => $check,
            'course_id' => $check->course_id,
        ]);
    }


    /**
    * Show the form for creating a new resource.
    * @param  int  $level_id
    * @return \Illuminate\Http\Response
    */
    public function create($level_id)
    {
        $level = Level::where('id',$level_id)->whereHas('course.courseUser',function($query){
            $query->where('user_id',auth()->id());
        })->with(['course.courseUser' => function($query){
            $query->where('user_id',auth()->id());
        }])->where('subscriber_id',auth()->user()->subscriber_id)->firstOrFail();
        // Check Student with Trainer Permission
        if($level->course->courseUser[0]->type != '2'){
            return redirect()->back();
        }
        return view('student.photos.create', [
        'id'    => $level_id,
        'course_id' => $level->course_id,
        ]);
    }

    /**
    * Store a newly created resource in storage.
    * @param  int  $level_id
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request,$level_id)
    {
        //Make Validation
        $this->rules['title'] = 'required|max:200';
        $this->rules['description'] = 'sometimes|nullable';
        $this->rules['image'] = 'required|image';
        $data = $this->validate($request, $this->rules);
        //Create Photo
        $category = Level::where('id',$level_id)->whereHas('course.courseUser',function($query){
            $query->where('user_id',auth()->id());
        })->where('subscriber_id',auth()->user()->subscriber_id)->with('course:id,category_id')->firstOrFail();
        $data['category_id'] = $category->course->category_id;
        $data['user_id'] = auth()->id();
        $data['admin_id'] = '0';
        $destination = "uploads/" . auth()->user()->subscriber_id . "/images/" . date("Y") . "/" . date("m") . "/";
        $data['image'] = UploadImages($destination, $request->file('image')); // Upload Image
        $photo = Photo::create($data);
        //Assign Photo to This Level
        PhotoCourse::create([
        'photo_id'      => $photo->id,
        'level_id'      => $level_id,
        'subscriber_id' => auth()->user()->subscriber_id,
        'user_id'      => auth()->id(),
        ]);
        session()->flash('success', trans("admin.add Successfully"));
        return redirect()->back();
    }

    /**
    * Show the form for creating a new resource.
    * @param  int  $level_id
    * @return \Illuminate\Http\Response
    */
    public function choose($level_id)
    {
        //Check Permission
        $level = Level::where('id',$level_id)->whereHas('course.courseUser',function($query){
            $query->where('user_id',auth()->id());
        })->where('subscriber_id',auth()->user()->subscriber_id)->with(['course.courseUser' => function($query){
            $query->where('user_id',auth()->id());
        }])->with('course:id,category_id')->firstOrFail();
        // Check Student with Trainer Permission
        if($level->course->courseUser[0]->type != '2'){
            return redirect()->back();
        }
        //Admin Photos
        if(request()->filled('stage')){
            $photos = Photo::where('admin_id','!=','0')->where('stage_id',request()->stage)->where('category_id',$level->course->category_id)->paginate(40);
        }else{
            $photos = Photo::where('admin_id','!=','0')->where('category_id',$level->course->category_id)->paginate(40);
        }
        //stages
        $stages = Stage::all();
        return view('student.photos.choose', [
        'id'    => $level_id,
        'course_id'    => $level->course_id,
        'photos'=> $photos,
        'stages'=> $stages,
        ]);
    }

    /**
    * Store a newly created resource in storage.
    * @param  int  $level_id
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function storeChoose(Request $request,$level_id)
    {
        //Check Permission
        $category = Level::where('id',$level_id)->whereHas('course.courseUser',function($query){
            $query->where('user_id',auth()->id());
        })->where('subscriber_id',auth()->user()->subscriber_id)->with(['course.courseUser' => function($query){
            $query->where('user_id',auth()->id());
        }])->with('course:id,category_id')->firstOrFail();
        // Check Student with Trainer Permission
        if($category->course->courseUser[0]->type != '2'){
            return redirect()->back();
        }
        //Assign Photo to This Level
        foreach ($request->photo_id as $key => $value) {
            PhotoCourse::UpdateOrCreate([
            'photo_id'      => $value,
            'level_id'      => $level_id,
            'subscriber_id' => auth()->user()->subscriber_id],
            ['user_id'      => auth()->id()
            ]);
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
    public function edit($id)
    {
        //Check Permissions
        $check = PhotoCourse::where('subscriber_id',auth()->user()->subscriber_id)
        ->whereHas('level',function($query){
            $query->whereHas('course.courseUser',function($query){
                $query->where('user_id',auth()->id());
            });
        })->with(['level.course.courseUser' => function($query){
            $query->where('user_id',auth()->id());
        }])->where('photo_id',$id)->firstOrFail();
        // Check Student with Trainer Permission
        if($check->level->course->courseUser[0]->type != '2'){
            return redirect()->back();
        }
        //Find Photo
        $photos = Photo::where('admin_id','0')->where('id',$id)->firstOrFail();
        return view('student.photos.edit', [
        'title' => trans("admin.edit photos") . ' : ' . $photos->title,
        'edit'  => $photos,
        'id'  => $check->level_id,
        'course_id'  => $check->level->course_id,
        ]);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        $photo = Photo::where('admin_id','0')->where('id',$id)->firstOrFail();
        //Check Permissions
        $check = PhotoCourse::where('subscriber_id',auth()->user()->subscriber_id)
        ->whereHas('level',function($query){
            $query->whereHas('course.courseUser',function($query){
                $query->where('user_id',auth()->id());
            });
        })->where('photo_id',$id)->firstOrFail();
        // Make Validation
        $this->rules['title'] = 'required|max:200';
        $this->rules['description'] = 'sometimes|nullable';
        $this->rules['image'] = 'sometimes|nullable|image';
        $data = $this->validate($request, $this->rules);
        //Update Data
        $data['user_id'] = auth()->id();
        if ($request->hasFile('image')) {
            if (file_exists(public_path('uploads/' . $photo->image))) {
                @unlink(public_path('uploads/' . $photo->image));
            }
            $destination = "uploads/" . auth()->user()->subscriber_id . "/images/" . date("Y") . "/" . date("m") . "/";
            $data['image'] = UploadImages($destination, $request->file('image')); // Upload Image
        }
        $photo->update($data);
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
    public function destroy($id)
    {
        //Check Permissions
        $check = PhotoCourse::where('subscriber_id',auth()->user()->subscriber_id)
        ->whereHas('level',function($query){
            $query->whereHas('course.courseUser',function($query){
                $query->where('user_id',auth()->id());
            });
        })->with(['level.course.courseUser' => function($query){
            $query->where('user_id',auth()->id());
        }])->where('photo_id',$id)->firstOrFail();
        // Check Student with Trainer Permission
        if($check->level->course->courseUser[0]->type != '2'){
            return redirect()->back();
        }
        //Find And Delete
        $photo = Photo::findOrFail($id);
        if($photo->admin_id == '0'){
            //IF This Photo Created By Trainer
            if (file_exists(public_path('uploads/' . $photo->image))) {
                @unlink(public_path('uploads/' . $photo->image));
            }
            $photo->delete();
        }else{
            //IF This Photo Created By Admin
            PhotoCourse::where('photo_id',$id)->where('level_id',$check->level_id)->delete();
        }
        session()->flash('success', trans("admin.delete Successfully"));
        return redirect()->back();
    }




}
