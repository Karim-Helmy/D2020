<?php

namespace App\Http\Controllers\api\father;

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
     * [message index of son]
     * @param  [int] $user_id [User ID (son id)]
     * api_url: api/father/messages/{user_id}  [method:get]
     * @return [json]
     */
    public function index($user_id)
    {
        // Check father_id of this user
        $user = User::where('id',$user_id)->where('father_id',auth()->id())->first();
        if(!$user){
            return sendError(trans('login.Please Check Your Data'));
        }
        //Get messages
        $message = CourseUser::where('user_id',$user_id)->groupBy('user_id')->with('course')->has('trainer')->get();
        $messages = $message->map(function($course){
            $trainer = CourseUser::with('user')->where('course_id',$course->course_id)->where('type','2')->first();
            return [
                'id'   => $trainer->user_id ?? '',
                'name'        => $trainer->user->name ?? '',
                'type'        => $trainer->user->type ?? '',
                'photo'       => $trainer->user->photo ?? '',
                'mobile'      => $trainer->user->mobile ?? '',
            ];
        });
        $supervisor = User::where('subscriber_id',$user->subscriber_id)->where('type','1')->first();
        $supervisor_data = [
            'id'   => $supervisor->id ?? '',
            'name'        => $supervisor->name ?? '',
            'type'        => $supervisor->type ?? '',
            'photo'       => $supervisor->photo ?? '',
            'mobile'      => $supervisor->mobile ?? '',
        ];
        $messages = collect($messages)->prepend($supervisor_data);
        return response([
            "status" => true,
            "message" => trans('admin.messages'),
            "son_name" => $user->name,
            "father_name" => auth()->user()->name ?? '',
            "data"    => $messages,
        ],200);
    }

    /**
     * [Show Messages Of Sender]
     * @param  [int] $sender_id,$user_id [Sender ID (Teacher Or Supervisor id) - User_id]
     * api_url: api/father/messages/show  [method:get]
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
        $son = User::where('father_id',auth()->id())->get(['name']);
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
            "son_name" => $son,
            "father_name" => auth()->user()->name ?? '',
            "trainer_name" => $username,
            "data"    => $all,
        ],200);
    }

    /**
     * [send Message From Father]
     * @param  Request $request [sender_id,son_id,message_content]
     * api_url: api/father/messages/send [method:post]
     * @return [json]           [message data and message success]
     */
    public function send(Request $request)
    {
        //Make Validation
        $validator = \Validator::make($request->all(), [
            "receiver_id" => "required|exists:users,id",
            'subject'     =>'required|max:150',
            'message'     =>'required',
        ]);
        //If Validation Errors
        if ($validator->fails()) {
            return sendError(implode(',',$validator->errors()->all()));
        }
        //Check receiver_id of same school with sons of father
        $user = User::where('subscriber_id',auth()->user()->subscriber_id)->where('id',$request->receiver_id)->first();
        if(!$user){
            return sendError(trans('login.Please Check Your Data'));
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
