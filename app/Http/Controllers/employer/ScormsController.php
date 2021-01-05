<?php

namespace App\Http\Controllers\Employer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ScormCourse;
use App\Scorm;
use App\Level;
use App\Stage;
use App\User;

class ScormsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  int  $level_id
     * @return \Illuminate\Http\Response
     */
    public function index($level_id,$subscriber_id)
    {
        if(!userSubscriber($subscriber_id)){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }
        // Check Permission
        $check = Level::where('id',$level_id)->firstOrFail();
        //Get Scorms
        $scorms = ScormCourse::where('level_id',$level_id)->where('subscriber_id',$subscriber_id)->with('scorm')->orderBy('id','desc')->get();
        return view('employer.scorms.index', [
            'title'    => trans('admin.Scorms'),
            'scorms' => $scorms,
            'id' => $level_id,
            'course_id' => $check->course_id,
            'subscriber_id'    => $subscriber_id,
        ]);
    }

    /**
    * [show Details Of packages]
    * @param  [int] $id [Id of packages]
    * @return [array]     [description]
    */
    public function play($id,$subscriber_id)
    {
        $scorms = ScormCourse::with('scorm')->where('scorm_id',$id)->firstOrFail();
        $path = public_path('uploads/'.$scorms->scorm->scorm.'/imsmanifest.xml');
        if(file_exists($path)){
            $xml = file_get_contents($path);
            $xml = simplexml_load_string($xml);
            $content = json_decode(json_encode($xml),TRUE);
            $link = $content['resources']['resource']['@attributes']['href'];
        }else{
            $link = "index.html";
        }
        return view('employer.scorms.show', [
        'title' => trans("admin.Scorms"),
        'scorm' => $scorms->scorm->scorm,
        'link' => $link,
        ]);

    }

   /**
    * Show the form for creating a new resource.
    * @param  int  $level_id
    * @return \Illuminate\Http\Response
    */
   public function choose($level_id,$subscriber_id)
   {
       if(!userSubscriber($subscriber_id)){
           return redirect('admin/index')->with([
               'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
           ]);
       }
       //Check Permission
       $level = Level::where('id',$level_id)->where('subscriber_id',$subscriber_id)->with('course:id,category_id')->firstOrFail();
       //Admin Scorms
       if(request()->filled('stage')){
           $scorms = Scorm::where('admin_id','!=','0')->where('stage_id',request()->stage)->whereHas('categories',function($query) use($level){
               $query->where('category_id',$level->course->category_id);
           })->paginate(40);
       }else{
           $scorms = Scorm::where('admin_id','!=','0')->whereHas('categories',function($query) use($level){
               $query->where('category_id',$level->course->category_id);
           })->paginate(40);
       }
       //stages
       $stages = Stage::all();
       return view('employer.scorms.choose', [
           'id'    => $level_id,
           'course_id'    => $level->course_id,
           'scorms'=> $scorms,
           'stages'=> $stages,
           'subscriber_id'    => $subscriber_id,
       ]);
   }

   /**
   * Store a newly created resource in storage.
   * @param  int  $level_id
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function storeChoose(Request $request,$level_id,$subscriber_id)
  {
      if(!userSubscriber($subscriber_id)){
          return redirect('admin/index')->with([
              'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
          ]);
      }
     //Check Permission
      $category = Level::where('id',$level_id)->where('subscriber_id',$subscriber_id)->with('course:id,category_id')->firstOrFail();
      //Assign Scorm to This Level
      foreach ($request->scorm_id as $key => $value) {
          ScormCourse::UpdateOrCreate([
          'scorm_id'      => $value,
          'level_id'      => $level_id,
          'subscriber_id' => $subscriber_id],
          ['user_id'      => User::where('type','1')->where('subscriber_id',$subscriber_id)->first()->id
          ]);
      }
      session()->flash('success', trans("admin.add Successfully"));
      return redirect()->back();
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
            //Check Permissions
            $check = ScormCourse::where('subscriber_id',$subscriber_id)
            ->where('scorm_id',$id)->firstOrFail();
            //Find And Delete
            $scorm = Scorm::findOrFail($id);
            if($scorm->admin_id != '0'){
                //IF This Scorm Created By Admin
                ScormCourse::where('scorm_id',$id)->where('level_id',$check->level_id)->delete();
            }
        }


}
