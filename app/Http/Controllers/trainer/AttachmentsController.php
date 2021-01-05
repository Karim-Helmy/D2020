<?php

namespace App\Http\Controllers\trainer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AttachmentCourse;
use App\Attachment;
use App\Level;
use App\Stage;

class AttachmentsController extends Controller
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
        //Get Attachments
        $attachments = AttachmentCourse::where('level_id',$level_id)->where('subscriber_id',auth()->user()->subscriber_id)->with('attachment.user')->orderBy('id','desc')->get();
        return view('trainer.attachments.index', [
            'attachments' => $attachments,
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
        return view('trainer.attachments.create', [
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
       $this->rules['image'] = 'required|mimes:doc,csv,xlsx,xls,docx,ppt,odt,ods,odp,pdf,zip';
       $data = $this->validate($request, $this->rules);
       //Create Attachment
       $category = Level::where('id',$level_id)->whereHas('course.courseUser',function($query){
           $query->where('user_id',auth()->id());
       })->where('subscriber_id',auth()->user()->subscriber_id)->with('course:id,category_id')->firstOrFail();
       $data['category_id'] = $category->course->category_id;
       $data['user_id'] = auth()->id();
       $data['admin_id'] = '0';
       $destination = public_path('uploads/uploads/'). auth()->user()->subscriber_id . "/attachments/" . date("Y") . "/" . date("m") . "/";
       $destination_database = "uploads/" . auth()->user()->subscriber_id . "/attachments/" . date("Y") . "/" . date("m") . "/";
       //Setting For Name file and Path
       $file = request()->file('image');
       $name = $file->getClientOriginalName(); // get image name
       $extension = $file->getClientOriginalExtension(); // get image extension
       $sha1 = sha1($name); // hash the image name
       $random = rand(1, 1000000); // Random To Name
       $name_database = $random . "_" . date("y-m-d-h-i-s") . "_" . $sha1; // To use it without extension
       $fileName = $random . "_" . date("y-m-d-h-i-s") . "_" . $sha1 . "." . $extension; // create new name for the file
       $file->move($destination, $fileName); // Upload Attachment
       $data['attachments'] = $destination_database . $fileName; // Create Name To Send It
       $attachment = Attachment::create($data);
       //Assign Attachment to This Level
       AttachmentCourse::create([
           'attachment_id'      => $attachment->id,
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
       //Admin Attachments
       if(request()->filled('stage')){
           $attachments = Attachment::where('admin_id','!=','0')->where('stage_id',request()->stage)->where('category_id',$level->course->category_id)->paginate(40);
       }else{
           $attachments = Attachment::where('admin_id','!=','0')->where('category_id',$level->course->category_id)->paginate(40);
       }
       //stages
       $stages = Stage::all();
       return view('trainer.attachments.choose', [
           'id'    => $level_id,
           'course_id'    => $level->course_id,
           'attachments'=> $attachments,
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
      //Assign Attachment to This Level
      foreach ($request->attachment_id as $key => $value) {
          AttachmentCourse::UpdateOrCreate([
              'attachment_id'      => $value,
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
           $check = AttachmentCourse::where('subscriber_id',auth()->user()->subscriber_id)
           ->whereHas('level',function($query){
               $query->whereHas('course.courseUser',function($query){
                   $query->where('user_id',auth()->id());
               });
           })->with('level')->where('attachment_id',$id)->firstOrFail();
           //Find Attachment
           $attachments = Attachment::where('admin_id','0')->where('id',$id)->firstOrFail();
           return view('trainer.attachments.edit', [
               'title' => trans("admin.edit attachments") . ' : ' . $attachments->title,
               'edit'  => $attachments,
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
           $attachment = Attachment::where('admin_id','0')->where('id',$id)->firstOrFail();
           //Check Permissions
           $check = AttachmentCourse::where('subscriber_id',auth()->user()->subscriber_id)
           ->whereHas('level',function($query){
               $query->whereHas('course.courseUser',function($query){
                   $query->where('user_id',auth()->id());
               });
           })->where('attachment_id',$id)->firstOrFail();
           // Make Validation
           $this->rules['title'] = 'required|max:200';
           $this->rules['description'] = 'sometimes|nullable';
           $this->rules['image'] = 'sometimes|nullable|mimes:doc,csv,xlsx,xls,docx,ppt,odt,ods,odp,pdf,zip';
           $data = $this->validate($request, $this->rules);
           //Update Data
           $data['user_id'] = auth()->id();
           if ($request->hasFile('image')) {
              if (file_exists(public_path('uploads/' . $attachment->attachments))) {
                  @unlink(public_path('uploads/' . $attachment->attachments));
              }
              $destination = public_path('uploads/uploads/'). auth()->user()->subscriber_id . "/attachments/" . date("Y") . "/" . date("m") . "/";
              $destination_database = "uploads/" . auth()->user()->subscriber_id . "/attachments/" . date("Y") . "/" . date("m") . "/";
              //Setting For Name file and Path
              $file = request()->file('image');
              $name = $file->getClientOriginalName(); // get image name
              $extension = $file->getClientOriginalExtension(); // get image extension
              $sha1 = sha1($name); // hash the image name
              $random = rand(1, 1000000); // Random To Name
              $name_database = $random . "_" . date("y-m-d-h-i-s") . "_" . $sha1; // To use it without extension
              $fileName = $random . "_" . date("y-m-d-h-i-s") . "_" . $sha1 . "." . $extension; // create new name for the file
              $file->move($destination, $fileName); // Upload Attachment
              $data['attachments'] = $destination_database . $fileName; // Create Name To Send It
          }
           $attachment->update($data);
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
            $check = AttachmentCourse::where('subscriber_id',auth()->user()->subscriber_id)
            ->whereHas('level',function($query){
                $query->whereHas('course.courseUser',function($query){
                    $query->where('user_id',auth()->id());
                });
            })->where('attachment_id',$id)->firstOrFail();
            //Find And Delete
            $attachment = Attachment::findOrFail($id);
            if($attachment->admin_id == '0'){
                //IF This Attachment Created By Trainer
                if (file_exists(public_path('uploads/' . $attachment->attachments))) {
                    @unlink(public_path('uploads/' . $attachment->attachments));
                }
                $attachment->delete();
            }else{
                //IF This Attachment Created By Admin
                AttachmentCourse::where('attachment_id',$id)->where('level_id',$check->level_id)->delete();
            }
        }


}
