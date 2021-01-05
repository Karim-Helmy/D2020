<?php

namespace App\Http\Controllers\Super;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PhotoCourse;
use App\Photo;
use App\Level;
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
        $check = Level::where('id',$level_id)->where('subscriber_id',auth()->user()->subscriber_id)->whereHas('course',function($query){
            $query->where('subscriber_id',auth()->user()->subscriber_id);
        })->firstOrFail();
        //Get Photos
        $photos = PhotoCourse::where('level_id',$level_id)->where('subscriber_id',auth()->user()->subscriber_id)->with('photo.user')->orderBy('id','desc')->get();
        return view('super.photos.index', [
            'photos' => $photos,
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
         $check = PhotoCourse::where('subscriber_id',auth()->user()->subscriber_id)
        ->where('photo_id',$id)->firstOrFail();
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
     }



}
