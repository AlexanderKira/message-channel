<?php

namespace App\Http\Controllers\API\Reply;

use App\Http\Controllers\Controller;
use App\Http\Requests\Message\ReplyMessageRequest;
use App\Http\Resources\Message\ReplyResource;
use App\Models\Reply;

class ReplyController extends Controller
{

    public function store(ReplyMessageRequest $request): ReplyResource
    {
        $data = $request->validated();
        $reply = new Reply();
        $reply->content = $data['content'];
        $reply->message_id = $data['message_id'];
        $reply->save();

        return new ReplyResource($reply);
    }

}
