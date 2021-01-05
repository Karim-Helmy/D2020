<?php

namespace App\Http\Controllers\trainer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VideoCourse;
use App\Video;
use App\Level;
use App\Stage;
use App\Rules\ValidateYoutube;

class VideosController extends Controller
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
        })->firstOrFail();
        //Get Videos
        $videos = VideoCourse::where('level_id',$level_id)->where('subscriber_id',auth()->user()->subscriber_id)->with('video.user')->orderBy('id','desc')->get();
        return view('trainer.videos.index', [
            'videos' => $videos,
            'id' => $level_id,
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
        })->where('subscriber_id',auth()->user()->subscriber_id)->firstOrFail();
        return view('trainer.videos.create', [
            'id'        => $level_id,
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
       $this->rules['link'] = 'required|url';
       $this->rules['link'] = [new ValidateYoutube];
       $data = $this->validate($request, $this->rules);
       //Create Video
       $category = Level::where('id',$level_id)->whereHas('course.courseUser',function($query){
           $query->where('user_id',auth()->id());
       })->where('subscriber_id',auth()->user()->subscriber_id)->with('course:id,category_id')->firstOrFail();
       $data['category_id'] = $category->course->category_id;
       $data['user_id'] = auth()->id();
       $data['admin_id'] = '0';
       $video = Video::create($data);
       //Assign Video to This Level
       VideoCourse::create([
           'video_id'      => $video->id,
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
       })->where('subscriber_id',auth()->user()->subscriber_id)->with('course:id,category_id')->firstOrFail();
       //Admin Videos
       if(request()->filled('stage')){
           $videos = Video::where('admin_id','!=','0')->where('stage_id',request()->stage)->where('category_id',$level->course->category_id)->paginate(40);
       }else{
           $videos = Video::where('admin_id','!=','0')->where('category_id',$level->course->category_id)->paginate(40);
       }
       //stages
       $stages = Stage::all();
       return view('trainer.videos.choose', [
           'id'    => $level_id,
           'course_id'    => $level->course_id,
           'videos'=> $videos,
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
      })->where('subscriber_id',auth()->user()->subscriber_id)->with('course:id,category_id')->firstOrFail();
      //Assign Video to This Level
      foreach ($request->video_id as $key => $value) {
          VideoCourse::UpdateOrCreate(
              [
              'video_id'      => $value,
              'level_id'      => $level_id,
              'subscriber_id' => auth()->user()->subscriber_id
              ],
              ['user_id'      => auth()->id()]
          );
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
           $check = VideoCourse::where('subscriber_id',auth()->user()->subscriber_id)
           ->whereHas('level',function($query){
               $query->whereHas('course.courseUser',function($query){
                   $query->where('user_id',auth()->id());
               });
           })->with('level')->where('video_id',$id)->firstOrFail();
           //Find Video
           $videos = Video::where('admin_id','0')->where('id',$id)->firstOrFail();
           return view('trainer.videos.edit', [
               'title' => trans("admin.edit videos") . ' : ' . $videos->title,
               'edit'  => $videos,
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
           //Check Permissions
           $check = VideoCourse::where('subscriber_id',auth()->user()->subscriber_id)
           ->whereHas('level',function($query){
               $query->whereHas('course.courseUser',function($query){
                   $query->where('user_id',auth()->id());
               });
           })->where('video_id',$id)->firstOrFail();
           // Make Validation
           $this->rules['title'] = 'required|max:200';
           $this->rules['description'] = 'sometimes|nullable';
           $this->rules['link'] = 'required|url';
           $this->rules['link'] = [new ValidateYoutube];
           $data = $this->validate($request, $this->rules);
           //Update Data
           $data['user_id'] = auth()->id();
           Video::where('admin_id','0')->where('id',$id)->update($data);
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
        public function destroy()
        {
            if (request()->filled('id')) {
                $id = request()->id;
            }
            //Check Permissions
            $check = VideoCourse::where('subscriber_id',auth()->user()->subscriber_id)
            ->whereHas('level',function($query){
                $query->whereHas('course.courseUser',function($query){
                    $query->where('user_id',auth()->id());
                });
            })->where('video_id',$id)->firstOrFail();
            //Find And Delete
            $video = Video::findOrFail($id);
            if($video->admin_id == '0'){
                //IF This Video Created By Trainer
                $video->delete();
            }else{
                //IF This Video Created By Admin
                VideoCourse::where('video_id',$id)->where('level_id',$check->level_id)->delete();
            }
        }


}
