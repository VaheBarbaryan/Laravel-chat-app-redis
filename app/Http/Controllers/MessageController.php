<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Models\UserMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\PrivateMessageEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class MessageController extends Controller
{
    public function conversation($userId)
    {
        // $users= User::where('id','!=',$userId)->get(); ************************
        $userId = auth()->user()->id;
        $users = DB::table('users')
            ->where('id', '!=', $userId)
            ->get();

        foreach ($users as $user) {
            $conversation = DB::table('user_messages')
                ->where(function ($query) use ($user, $userId) {
                    $query->where('sender_id', $user->id)
                        ->where('receiver_id', $userId)
                        ->orWhere('sender_id', $userId)
                        ->where('receiver_id', $user->id);
                })
                ->orderBy('id', 'desc')
                ->first();

            if ($conversation) {
                $message = DB::table('messages')
                    ->where('id', $conversation->message_id)
                    ->value('message');
                $unread_message_count = DB::table('user_messages')
                    ->where(function ($query) use ($user, $userId) {
                        $query->where('sender_id', $user->id)
                            ->where('receiver_id', $userId);
                            // ->orWhere('sender_id', $userId)
                            // ->where('receiver_id', $user->id);
                    })
                    ->where('seen_status', 0)->count();
                $user->lastMessage = $message;
                $user->unreadMessages = $unread_message_count;
            } else {
                $user->lastMessage = null;
            }
        }


        // dd($users);
        $friendInfo = User::findOrFail($userId);
        $myInfo = User::find(Auth::id());
        $response = [
            'users' => $users,
            'friendInfo' =>$friendInfo,
            'myInfo' => $myInfo,
            'userId' => $userId
        ];
        return Response::json($response, 200);
    }

    public function getMessages($user_id, $friend_id)
    {
        DB::table('user_messages')
            ->where('sender_id', $friend_id)
            ->where('receiver_id', auth()->user()->id)
            ->update(['seen_status' => 1]);
        // $messages = UserMessage::with('messages', 'user')
        //     ->where(['sender_id' => $user_id, 'receiver_id' => $friend_id])
        //     ->orWhere(function ($query) use ($friend_id, $user_id) {
        //         $query->where(['sender_id' => $friend_id, 'receiver_id' => $user_id]);
        //     })
        //     ->groupBy('message_id')
        //     ->havingRaw('SUM(CASE WHEN receiver_id = ? THEN 1 ELSE 0 END) > 0', [$friend_id])
        //     ->orderBy('created_at', 'DESC')
        //     ->get();


        $messages = UserMessage::with('messages', 'user')
        ->whereIn('id', function ($query) use ($user_id, $friend_id) {
            $query->selectRaw('MAX(id)')
                ->from('user_messages')
                ->where(function ($subQuery) use ($user_id, $friend_id) {
                    $subQuery->where('sender_id', $user_id)
                        ->where('receiver_id', $friend_id);
                })
                ->orWhere(function ($subQuery) use ($user_id, $friend_id) {
                    $subQuery->where('sender_id', $friend_id)
                        ->where('receiver_id', $user_id);
                })
                ->groupBy('message_id');
        })
        ->orderBy('created_at', 'DESC')
        ->get();

            // dd($messages);
        $response = [
            'messages' => $messages
        ];
        return Response::json($response, 200);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required',
            'receiver_id' => 'required'
        ]);

        $sender_id = Auth::id();
        $receiver_id = $request->receiver_id;

        DB::beginTransaction();
        try {
            // $message->users()->attach($sender_id, ['receiver_id' => $receiver_id]);
            $message = new Message();
            $message->message = $request->message;
            $message->save();
            // Create a new entry in the `user_messages` table for the sender

            DB::table('user_messages')->insert([
                'message_id' => $message->id,
                'sender_id' => $receiver_id,
                'receiver_id' => $sender_id,
                'seen_status' => 1, // Set the `read` status as true for the sender
            ]);

            // Create a new entry in the `user_messages` table for the recipient
            DB::table('user_messages')->insert([
                'message_id' => $message->id,
                'sender_id' => $sender_id,
                'receiver_id' => $receiver_id,
                'seen_status' => 0, // Set the `read` status as false for the recipient
            ]);
            $sender = User::where('id', $sender_id)->first();

            $data = [];
            $data['sender_id'] = $sender_id;
            $data['sender_name'] = auth()->user()->name;
            $data['receiver_id'] = $receiver_id;
            $data['content'] = $message->message;
            $data['created_at'] = $message->created_at;
            $data['message_id'] = $message->id;

            event(new PrivateMessageEvent($data));

            $response = [
                'data' => $data,
                'status' => 1,
                'message' => 'Message sent successfully'
            ];

            DB::commit();
            return Response::json($response);
        } catch (\Exception $err) {
            DB::rollBack();
            dd($err->getMessage());
        }

    }
}
