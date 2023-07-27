<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SupportMessage;
use App\Events\SendSupportMessage;
use App\Models\SupportMessageDocument;
use Illuminate\Http\Request;
use App\Models\{User,ChatList,ChatListMessage,ChatListMessageFile,Ride};
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Http\Resources\{ChatResource,ChatListMessageResource};
use Exception;
use File;

class ChatController extends Controller
{
    public function chatLists(Request $request)
    {
        try {
            if ($request->has('search')) {
                $chatLists = ChatList::where('from', Auth::user()->id)->orWhere('to', Auth::user()->id)
                    ->with(['toUser' => function ($q) use ($request) {
                        $q->where('username', 'like', '%' . $request->search . '%');
                    }], ['fromUser' => function ($q) use ($request) {
                        $q->where('username', 'like', '%' . $request->search . '%');
                    }], ['messages' => function ($q) {
                        $q->orderBy('id', 'desc')->first();
                    }])->orderBy('created_at', 'desc')->paginate(10);
            } else {
                $chatLists = ChatList::where('from', Auth::user()->id)->orWhere('to', Auth::user()->id)
                    ->with('toUser', 'fromUser', 'messages')->orderBy('created_at', 'desc')->paginate(10);
            }
            if ($chatLists) {
                $chatLists = ChatResource::collection($chatLists)->response()->getData(true);
                return apiresponse(true, "Chat list found", $chatLists);
            } else {
                return apiresponse(false, "Something went wrong");
            }
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function createChatList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }
        try {
            $chatList = ChatList::where('from', Auth::user()->id)->where('to', $request->user_id)->first();
            if (!$chatList) {
                $chatList = ChatList::where('to', Auth::user()->id)->where('from', $request->user_id)->first();
            }
            if (!$chatList) {
                $chatListData = [
                    'to' => $request->user_id,
                    'from' => Auth::user()->id,
                ];
                $chatList = ChatList::create($chatListData);
            }
            return apiresponse(true, 'Chat list', $chatList);
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function sendMessage(Request $request)
    {
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
            $chatList = ChatList::where('from', Auth::user()->id)->where('to', $request->user_id)->first();
            if (!$chatList) {
                $chatList = ChatList::where('to', Auth::user()->id)->where('from', $request->user_id)->first();
            }
            if (!$chatList) {
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
            if ($chatMessageSend) {
                $flag = true;
                if ($request->type != 'text') {
                    $res = files_upload($request->file, 'MessageFile');
                    $messageFileData = [
                        'chat_list_message_id' => $chatMessageSend->id,
                        'name' => $res,
                    ];
                    if (ChatListMessageFile::create($messageFileData)) {
                        $flag = true;
                    }
                }
                if ($flag) {
                    $messages = ChatListMessage::where('id', $chatMessageSend->id)->with('toUser', 'fromUser', 'messagesFiles')->first();
                    $messages->ride_id = $request->ride_id;
                    broadcast(new \App\Events\Message($messages, $request->ride_id))->toOthers();
                    $title = 'You have a new message from ' . Auth::user()->username;
                    $body = $messages->message;
                    // return $messages->toUser->device_id;
                    SendNotification($messages->toUser->device_id, $title, $body);
                    saveNotification($title, $body, 'message', $messages->from, $messages->to);
                    return apiresponse(true, "Message sent", $messages);
                } else {
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

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message_id' => 'required'
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode('/n', $validator->errors()->all()));
        }

        try {
            $ChatListMessage = ChatListMessage::find($request->message_id);

            if (!$ChatListMessage) {
                return apiresponse(false, 'Message not found');
            }

            if ($ChatListMessage->messagesFiles) {
                foreach ($ChatListMessage->messagesFiles as $file) {
                    $filename = public_path('images/' . $file->name);
                    if (file_exists($filename)) {
                        File::delete($filename);
                    }
                    ChatListMessageFile::find($file->id)->delete();
                }
            }

            if ($ChatListMessage->delete()) {
                return apiresponse(true, 'Message deleted');
            } else {
                return apiresponse(false, 'Something went wrong');
            }
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function createSupportChatList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'faq_category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }

        try {
            $staff = User::where('role', 'staff')->first();

            if (!$staff) {
                return apiresponse(false, "Staff not found");
            }

            /*$staff = $staffs->where('support_category_id', $request->faq_category_id)->first();
            if ($staff == null) {
                $staff = $staffs->random(1)->first();
            }*/

            $chatList = ChatList::where('faq_category_id', $request->faq_category_id)->where(function ($query) use ($staff) {
                $query->where(['from' => Auth::user()->id, 'to' => $staff->id])->orWhere(['to' => Auth::user()->id, 'from' => $staff->id]);
            })->first();

            if (!$chatList) {
                $chatListData = [
                    'to' => $staff->id,
                    'from' => Auth::user()->id,
                    'faq_category_id' => $request->faq_category_id
                ];
                $chatList = ChatList::create($chatListData);
            }

            return apiresponse(true, 'Chat list', new ChatResource($chatList));
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function supportChatList(Request $request)
    {
        try {
            $chatLists = ChatList::where('from', Auth::user()->id)->orWhere('to', Auth::user()->id)
                ->with('toUser', 'fromUser', 'supportMessages', 'category')->orderBy('created_at', 'desc')->get();

            if ($chatLists->count() > 0) {
                $chatLists = ChatResource::collection($chatLists);
            }
            return apiresponse(true, "Chat list found", $chatLists);
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function sendSupportMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type' => 'required',
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode("\n", $validator->errors()->all()));
        }

        try {
            $chatList = ChatList::where('from', Auth::user()->id)->where('to', $request->user_id)->where('faq_category_id', $request->category_id)->first();

            if (!$chatList) {
                $chatList = ChatList::where('to', Auth::user()->id)->where('from', $request->user_id)->where('faq_category_id', $request->category_id)->first();
            }

            if (!$chatList) {
                $chatListData = [
                    'to' => $request->user_id,
                    'from' => Auth::user()->id,
                    'faq_category_id' => $request->category_id
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

            $chatMessageSend = SupportMessage::create($messageData);

            if ($request->type != 'text') {
                $res = files_upload($request->file, 'MessageFile');
                $messageFileData = [
                    'support_message_id' => $chatMessageSend->id,
                    'name' => $res,
                ];
                SupportMessageDocument::create($messageFileData);
            }

            $message = SupportMessage::where('id', $chatMessageSend->id)->with('toUser', 'fromUser', 'messagesFiles')->first();
            broadcast(new SendSupportMessage($message))->toOthers();
            $title = 'You have a new message from ' . Auth::user()->username;
            $body = $message->message;
            SendNotification($message->toUser->device_id, $title, $body);
            saveNotification($title, $body, 'message', $message->from, $message->to);

            return apiresponse(true, "Message sent", $message);
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function supportMessages(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chat_id' => 'required'
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode('\n', $validator->errors()->all()));
        }

        try {
            $chatList = ChatList::find($request->chat_id);
            if (!$chatList) {
                return apiresponse(false, "Chatlist not found");
            }
            $chatListMessages = SupportMessage::where('chat_list_id', $chatList->id)->with('toUser', 'fromUser', 'messagesFiles')->orderBy('created_at', 'desc')->paginate(10);
            if ($chatListMessages->count() > 0) {
                $chatListMessages = ChatListMessageResource::collection($chatListMessages)->response()->getData(true);
                SupportMessage::where('chat_list_id', $chatList->id)->update(['is_read' => 1]);
            }

            return apiresponse(true, 'Chat', $chatListMessages);

        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function deleteSupportMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message_id' => 'required'
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode('/n', $validator->errors()->all()));
        }

        try {
            $chatListMessage = SupportMessage::find($request->message_id);

            if (!$chatListMessage) {
                return apiresponse(false, 'Message not found');
            }

            if ($chatListMessage->messagesFiles) {
                foreach ($chatListMessage->messagesFiles as $file) {
                    $filename = public_path('images/' . $file->name);
                    if (file_exists($filename)) {
                        File::delete($filename);
                    }
                    SupportMessageDocument::find($file->id)->delete();
                }
            }

            $chatListMessage->delete();
            return apiresponse(true, 'Message deleted');
        } catch (Exception $e) {
            return apiresponse(false, $e->getMessage());
        }
    }

    public function endSupportChat(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chat_id' => 'required'
        ]);

        if ($validator->fails()) {
            return apiresponse(false, implode('/n', $validator->errors()->all()));
        }

        try {
            $chatlist = ChatList::where('id', $request->chat_id)->first();
            if (!$chatlist) {
                return apiresponse(false, "Chatlist not found");
            }

            $chatlist->delete();
            return apiresponse(true, "Chat has been ended successfully", ['data' => []]);
        } catch (Exception $exception) {
            return apiresponse(false, $exception->getMessage());
        }
    }
}
