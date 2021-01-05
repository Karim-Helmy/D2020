<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Message;
use App\User;

class MessagesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!userCan('messages')){
            return redirect('admin/index')->with([
                'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
            ]);
        }

        $message = Message::with('user.subscriber.package')->where('receiver_id',null)->orderBy('id', 'desc')->select('sender_id')->groupBy('sender_id')->get();
        return view('admin.messages.index', [
            'title' => trans('admin.show all messages'),
            'index' => $message,
        ]);
    }

    /**
     * Display the details resource.
     *param sender_id
     * @return \Illuminate\Http\Response
     */

     public function show($sender_id = null)
     {
         if(!userCan('messages')){
             return redirect('admin/index')->with([
                 'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
             ]);
         }

         $messages = Message::where([
             ['receiver_id',null],
             ['sender_id',$sender_id],
         ])->orWhere([
             ['receiver_id',$sender_id],
             ['sender_id',null],
         ])->orderBy('id', 'asc')->get();
          $user = User::where('id',$sender_id)->first();
          $username = $user->name ?? "No Name";
         $all = $messages->map(function($message) use($sender_id){
             if($message->receiver_id == $sender_id){
                 $type = 'send';
             }else{
                 $type = 'receive';
                 $message->increment('views');
             }
             return $data = [
                 'type' => $type,
                 'subject' => $message->subject,
                 'message' => $message->message,
                 'date' => $message->created_at,
             ];
         })->toArray();
         return view('admin.messages.show', [
             'title' => trans('admin.show all messages'),
             'messages' => $all,
             'username' => $username,
             'sender_id'=> $sender_id,
         ]);
     }

     /**
      * [save new message]
      * @param  Request $request [description]
      * @return [type]           [description]
      */
     public function send(Request $request,$sender_id = null){
         if(!userCan('messages')){
             return redirect('admin/index')->with([
                 'error' => trans('عفوا ليس لديك الصلاحية لمشاهدة هذه الصفحة'),
             ]);
         }

         $data = $this->validate($request, [
             'subject'=>'required|max:150',
             'message'=>'required',
         ]);
         $data['receiver_id'] = $sender_id;
         $message = Message::Create($data);
         session()->flash('success', trans("admin.sent Successfully"));
         return redirect()->back();
     }

}
