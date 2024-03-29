<?php

namespace App\Http\Controllers\API\Reply;

use App\Enums\MessageTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Message\ReplyMessageRequest;
use App\Http\Resources\Message\MessageResource;
use App\Http\Resources\Message\ReplyResource;
use App\Models\Message;
use App\Models\Reply;
use Illuminate\Support\Facades\Auth;

class ReplyController extends Controller
{

    public function store(ReplyMessageRequest $request, Reply $reply, Message $message): ReplyResource
    {
        $data = $request->validated();
        $reply->content = $data['content'];
        $reply->message_id = $message->id;

        if($message->type === MessageTypeEnum::PRIVATE){
            $user = Auth::user();
            if($message->sender_id === $user->id){
                $reply->save();
                return new ReplyResource($reply);
            }
            if($message->user_id === $user->id){
                $reply->save();
                return new ReplyResource($reply);
            }
            abort(403, 'This action is unauthorized.');
        }
        $reply->save();
        return new ReplyResource($reply);
    }

}
