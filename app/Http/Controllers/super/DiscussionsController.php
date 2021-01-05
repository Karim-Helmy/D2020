<?php

namespace App\Http\Controllers\Super;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DiscussionUser;
use App\CourseUser;
use App\Discussion;


class DiscussionsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  int  $course_id
     * @return \Illuminate\Http\Response
     */
    public function index($course_id)
    {
        // Check Permission
        $discussions = Discussion::where('course_id',$course_id)->where('subscriber_id',auth()->user()->subscriber_id)->whereHas('course',function($query){
            $query->where('subscriber_id',auth()->user()->subscriber_id);
        })->paginate(25);
        //Get Videos
        return view('super.discussions.index', [
            'discussions' => $discussions,
            'id' => $course_id,
        ]);
    }


        /**
         * Display the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
         */
        public function show($id)
        {
            $dis = Discussion::where('id',$id)->where('subscriber_id',auth()->user()->subscriber_id)->firstOrFail(); //For Check
            $discussions = DiscussionUser::where('discussion_id',$id)->with('discussion','user')->get();
            return view('super.discussions.show', [
                'discussions' => $discussions,
                'id'          => $id,
                'course_id'   => $dis->course_id,
            ]);
        }


            /**
            * Store a newly created resource in storage.
            * @param  int  $course_id
            * @param  \Illuminate\Http\Request  $request
            * @return \Illuminate\Http\Response
            */
           public function comment(Request $request,$id)
           {
               //Make Validation
               $this->rules['comment'] = 'required';
               $data = $this->validate($request, $this->rules);
               //Create Video
               Discussion::where('id',$id)->where('subscriber_id',auth()->user()->subscriber_id)->whereHas('course',function($query){
                   $query->where('subscriber_id',auth()->user()->subscriber_id);
               })->firstOrFail(); //For Check
               $data['discussion_id'] = $id;
               $data['user_id'] = auth()->id();
               $data['type'] = auth()->user()->type;
               $discussion = DiscussionUser::create($data);
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
            public function destroy()
            {
                if (request()->filled('id')) {
                    $id = request()->id;
                }
                //Find And Delete
                $discussion = Discussion::where('subscriber_id',auth()->user()->subscriber_id)->where('id',$id)->firstOrFail();
                $discussion->delete();
            }


}
