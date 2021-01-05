<?php

namespace App\Http\Controllers\Super;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\AttachmentCourse;
use App\Attachment;
use App\Level;


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
        $check = Level::where('id',$level_id)->where('subscriber_id',auth()->user()->subscriber_id)->whereHas('course',function($query){
            $query->where('subscriber_id',auth()->user()->subscriber_id);
        })->firstOrFail();
        //Get Attachments
        $attachments = AttachmentCourse::where('level_id',$level_id)->where('subscriber_id',auth()->user()->subscriber_id)->with('attachment.user')->orderBy('id','desc')->get();
        return view('super.attachments.index', [
            'attachments' => $attachments,
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
         $check = AttachmentCourse::where('subscriber_id',auth()->user()->subscriber_id)
         ->where('attachment_id',$id)->firstOrFail();
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
