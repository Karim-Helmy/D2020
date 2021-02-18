<?php

namespace App\Http\Controllers\api\student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Father\User;
use App\Father\Message;
use App\Father\CourseUser;
use App\Subscriber;

class MessagesController extends Controller
{

    /**
     * [message index]
     * @param  [int] $user_id [Auth ID ]
     * api_url: api/student/messages/{user_id}  [method:get]
     * @return [json]
     */
    public function index()
    {
        // Check student_id of this user
        $user = User::where('id',auth()->id())->first();
        if(!$user){
            return sendError(trans('login.Please Check Your Data'));
        }
        //Get messages
        $message = Message::where('sender_id',auth()->id())->groupBy('sender_id')->get();


        return response([
            "status" => true,
            "message" => 'الرسائل',
            "User" => $user->name,
            "data"    => $message,
        ],200);
    }

    /**
     * [Show Messages Of Sender]
     * @param  [int] $sender_id,$user_id [Sender ID (Teacher Or Supervisor id) - User_id]
     * api_url: api/student/messages/show  [method:get]
     * @return [json]
     */
    public function show()
    {
        //Put Requests In Variables
        $user_id   = auth()->id();
        $sender_id = request()->sender_id;
        //Check user_id , sender id is found
        if(!request()->filled('sender_id')){
            return sendError(trans('login.Please Check Your Data'));
        }
        //Get Sons
        $son = User::where('id',auth()->id())->get(['name']);
        $son = implode(' - ',$son->pluck('name')->toArray());
        //Get Messages
        $messages = Message::where([
            ['receiver_id',$user_id],
            ['sender_id',$sender_id],
        ])->orWhere([
            ['receiver_id',$sender_id],
            ['sender_id',$user_id],
        ])->orderBy('id', 'asc')->get();
         $user = User::where('id',$sender_id)->first();
         $username = $user->name ?? "Super Admin";
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
                'date' => date('Y-m-d H:i:s',strtotime($message->created_at)),
            ];
        })->toArray();

        return response([
            "status" => true,
            "message" => trans('admin.messages'),
            "student_name" => $son,
            "trainer_name" => $username,
            "data"    => $all,
        ],200);
    }

    /**
     * [send Message From Student]
     * @param  Request $request [sender_id,son_id,message_content]
     * api_url: api/student/messages/send [method:post]
     * @return [json]           [message data and message success]
     */
    public function send(Request $request)
    {
        //Make Validation
        $validator = \Validator::make($request->all(), [
            'subject'     =>'required|max:150',
            'message'     =>'required',
        ]);
        //If Validation Errors
        if ($validator->fails()) {
            return sendError(implode(',',$validator->errors()->all()));
        }

        try{
            $request['sender_id']   = auth()->id();
            $message = Message::Create($request->all());
            return sendResponse(trans('admin.sent Successfully'),$message);
            //If Find Any Problem
        }catch(Exception $e){
            return sendError(trans('login.Please Check Your Data'));
        }
    }

}
