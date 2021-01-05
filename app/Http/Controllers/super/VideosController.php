<?php

namespace App\Http\Controllers\Super;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\VideoCourse;
use App\Video;
use App\Level;


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
        $check = Level::where('id',$level_id)->where('subscriber_id',auth()->user()->subscriber_id)->whereHas('course',function($query){
            $query->where('subscriber_id',auth()->user()->subscriber_id);
        })->firstOrFail();
        //Get Videos
        $videos = VideoCourse::where('level_id',$level_id)->where('subscriber_id',auth()->user()->subscriber_id)->with('video.user')->orderBy('id','desc')->get();
        return view('super.videos.index', [
            'videos' => $videos,
            'id' => $level_id,
            'course_id' => $check->course_id,
        ]);
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
         ->where('video_id',$id)->firstOrFail();
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
