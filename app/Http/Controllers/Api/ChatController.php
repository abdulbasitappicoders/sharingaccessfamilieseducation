<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User,ChatList,ChatListMessage,ChatListMessageFile,Ride};
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Http\Resources\{ChatResource,ChatListMessageResource};
use Exception;
use File;

class ChatController extends Controller
{

    public function chatLists(Request $request){
        $posts = Post::with(['comments' => function($q){
            $q->get(['comments.comment']);
        }])->get('posts.name');
        try {
            if($request->has('search')){
                $chatLists  = ChatList::where('from',Auth::user()->id)->orWhere('to',Auth::user()->id)
                            ->with(['toUser' => function($q) use ($request){
                                $q->where('username','like','%'.$request->search.'%');
                            }],['fromUser' => function($q) use ($request){
                                $q->where('username','like','%'.$request->search.'%');
                            }],['messages' => function($q){
                                $q->orderBy('id','desc')->first();
                            }])->orderBy('created_at','desc')->paginate(10);
            }else{
                $chatLists = ChatList::where('from',Auth::user()->id)->orWhere('to',Auth::user()->id)
                            ->with('toUser','fromUser','messages')->orderBy('created_at','desc')->paginate(10);
            }
            if($chatLists){
                $chatLists = ChatResource::collection($chatLists)->response()->getData(true);
                return apiresponse(true, "Chat list found", $chatLists);
            }else{
                return apiresponse(false, "Something went wrong");
            }
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
        
    }

    public function createChatList(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }
        try {
            $chatList = ChatList::where('from',Auth::user()->id)->where('to',$request->user_id)->first();
            if(!$chatList){
                $chatList = ChatList::where('to',Auth::user()->id)->where('from',$request->user_id)->first();
            }
            if(!$chatList){
                $chatListData = [
                    'to' => $request->user_id,
                    'from' => Auth::user()->id,
                ];
                $chatList = ChatList::create($chatListData);
            }
            return apiresponse(true, 'Chat list',$chatList);
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function sendMessage(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required',
            'ride_id' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }
        try {
            $flag = false;
            $chatList = ChatList::where('from',Auth::user()->id)->where('to',$request->user_id)->first();
            if(!$chatList){
                $chatList = ChatList::where('to',Auth::user()->id)->where('from',$request->user_id)->first();
            }
            if(!$chatList){
                $chatListData = [
                    'to' => $request->user_id,
                    'from' => Auth::user()->id,
                ];
                $chatList = ChatList::create($chatListData);
            }
            $messageData = [
                'chat_list_id' => $chatList->id,
                'type' => $request->type,
                'message' => $request->message,
                'to' => $request->user_id,
                'from' => Auth::user()->id,
            ];
            $chatMessageSend = ChatListMessage::create($messageData);
            if($chatMessageSend){
                $flag = true;
                if($request->type != 'text'){
                    $res = files_upload($request->file, 'MessageFile');
                    $messageFileData = [
                        'chat_list_message_id' => $chatMessageSend->id,
                        'name' => $res,
                    ];
                    if(ChatListMessageFile::create($messageFileData)){
                        $flag = true;
                    }
                }
                if($flag){
                    $messages = ChatListMessage::where('id',$chatMessageSend->id)->with('toUser','fromUser','messagesFiles')->first();
                    $messages->ride_id = $request->ride_id;
                    broadcast(new \App\Events\Message($messages,$request->ride_id))->toOthers();
                    $title = 'You have a new message from ' . Auth::user()->username;
                    $body = $messages->message;
                    // return $messages->toUser->device_id;
                    SendNotification($messages->toUser->device_id, $title, $body);
                    saveNotification($title,$body,'message',$messages->from,$messages->to);
                    return apiresponse(true, "Message sent", $messages);
                }else{
                    return apiresponse(false, "Something went wrong");
                }
            }

        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function chatMessages(Request $request){
        $validator = Validator::make($request->all(),[
           'ride_id' => 'required'
        ]);
        if($validator->fails()){
            return apiresponse(false, implode('\n',$validator->errors()->all()));
        }
        try {
            $ride = Ride::find($request->ride_id);
            if($ride){
                $chatList = ChatList::where('from',$ride->rider_id)->where('to',$ride->driver_id)->first();
                if(!$chatList){
                    $chatList = ChatList::where('to',$ride->rider_id)->where('from',$ride->driver_id)->first();
                }
                if(!$chatList){
                    $chatListData = [
                        'to' => $ride->rider_id,
                        'from' => $ride->driver_id,
                    ];
                    $chatList = ChatList::create($chatListData);
                }
                $ChatListMessages = ChatListMessage::where('chat_list_id',$chatList->id)->with('toUser','fromUser','messagesFiles')->orderBy('created_at','desc')->paginate(10);
                $chatListMessages = ChatListMessageResource::collection($ChatListMessages)->response()->getData(true);
                ChatListMessage::where('chat_list_id',$chatList->id)->update(['is_read' => 1]);
                return apiresponse(true,'Chat',$chatListMessages) ;
            }else{
                return apiresponse(true,'ride not found') ;
            }
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function destroy(Request $request){
        $validator = Validator::make($request->all(),[
            'message_id' => 'required'
        ]);
        if($validator->fails()){
            return apiresponse(false, implode('/n',$validator->errors()->all()));
        }
        try {
            $ChatListMessage = ChatListMessage::find($request->message_id);
            if($ChatListMessage){
                if($ChatListMessage->messagesFiles){
                    foreach($ChatListMessage->messagesFiles as $file){
                        $filename = public_path('images/MessageFile/'.$file->name);
                        if(file_exists($filename)){
                            File::delete($filename);
                        }
                        ChatListMessageFile::find($file->id)->delete();
                    }
                }
                if($ChatListMessage->delete()){
                    return apiresponse(true, 'Message deleted');
                }else{
                    return apiresponse(false, 'Something went wrong');
                }
            }
        } catch (Exception $e) {    
            return apiresponse(false, $e->getMessage());
        }
    }
}
