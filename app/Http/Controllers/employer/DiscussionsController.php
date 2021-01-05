<?php

namespace App\Http\Controllers\Employer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DiscussionUser;
use App\CourseUser;
use App\User;
use App\Discussion;


class DiscussionsController extends Controller
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
        $discussions = Discussion::where('course_id',$course_id)->where('subscriber_id',$subscriber_id)->whereHas('course',function($query) use($subscriber_id){
            $query->where('subscriber_id',$subscriber_id);
        })->get();
        //Get Videos
        return view('employer.discussions.index', [
            'title'    => trans('admin.discussions'),
            'discussions' => $discussions,
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
        return view('employer.discussions.create', [
            'id'    => $course_id,
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
       $data = $this->validate($request, $this->rules);
       //Create Discussion
       $data['course_id'] = $course_id;
       $data['user_id'] = User::where('type','1')->where('subscriber_id',$subscriber_id)->first()->id;
       $data['subscriber_id'] = $subscriber_id;
       $discussion = Discussion::create($data);
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
           $discussions = Discussion::where('subscriber_id',$subscriber_id)->where('id',$id)->firstOrFail();
           return view('employer.discussions.edit', [
               'title' => 'edit',
               'edit'  => $discussions,
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
       public function update(Request $request, $id,$subscriber_id)
       {
           if(!userSubscriber($subscriber_id)){
               return redirect('admin/index')->with([
                   'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
               ]);
           }
           //Check Permissions
           $discussions = Discussion::where('id',$id)->firstOrFail();
           // Make Validation
           $this->rules['title'] = 'required|max:200';
           $data = $this->validate($request, $this->rules);
           //Update Data
           $data['course_id'] = $discussions->course_id;
           $data['user_id'] = User::where('type','1')->where('subscriber_id',$subscriber_id)->first()->id;
           $data['subscriber_id'] = $subscriber_id;
           $discussions->update($data);
           // Success Message
           session()->flash('success', trans("admin.edit Successfully"));
           return  redirect()->back();
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
            $dis = Discussion::where('id',$id)->where('subscriber_id',$subscriber_id)->firstOrFail(); //For Check
            $discussions = DiscussionUser::where('discussion_id',$id)->with('discussion','user')->get();
            return view('employer.discussions.show', [
                'discussions' => $discussions,
                'id'          => $id,
                'course_id'   => $dis->course_id,
                'subscriber_id'    => $subscriber_id,
            ]);
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
                $discussion = Discussion::where('subscriber_id',$subscriber_id)->where('id',$id)->firstOrFail();
                $discussion->delete();
            }


            /**
             * Remove the specified resource from storage.
             *
             * @param  int  $id
             * @param  bool  $redirect
             * @return \Illuminate\Http\Response
             */
             public function destroyItem($subscriber_id)
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
                 $discussion = DiscussionUser::where('id',$id)->firstOrFail();
                 Discussion::where('id',$discussion->discussion_id)->where('subscriber_id',$subscriber_id)->firstOrFail(); //For Check
                 $discussion->delete();
             }


}
