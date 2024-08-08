<?php

namespace App\Http\Controllers\API\Message;

use App\Enums\MessageTypeEnum;
use App\Http\Builders\Filter\MessageFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Message\IndexMessageRequest;
use App\Http\Requests\Message\MessageRequest;
use App\Http\Resources\Message\MessageResource;
use App\Models\Message;
use App\Models\Reply;
use App\Models\User;
use App\Services\Filters\Message\FilterManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class MessageController extends Controller
{
    public function index(MessageFilter $filter): AnonymousResourceCollection
    {
        $messages = Message::filter($filter);

        if (!auth()->check()) {
            $messages = $messages->forUnauthorizedUser();
        }

        return MessageResource::collection($messages->get());
    }

    public function show(Message $message): MessageResource
    {
        if (Auth::check()) {
            if($message->type === MessageTypeEnum::PRIVATE){
                $user = Auth::user();
                if($message->recipient_id === $user->id){
                    return new MessageResource($message);
                }
                if($message->sender_id === $user->id){
                    return new MessageResource($message);
                }
                abort(403, 'This action is unauthorized.');
            }
            return new MessageResource($message);
        }
        if ($message->type === MessageTypeEnum::ALL) {
            return new MessageResource($message);
        }
        abort(403, 'This action is unauthorized.');
    }

    public function store(MessageRequest $request): MessageResource
    {
        $data = $request->validated();

        $message = new Message();
        $message->content = $data['content'];
        $message->type = $data['type'];

        if($data['type'] === MessageTypeEnum::PRIVATE->value){
            $message->recipient_id = $data['recipient_id'];
        }

        $message->save();

        return new MessageResource($message);
    }

    public function update(Message $message, Request $request): MessageResource
    {
        Gate::allowIf(fn (User $user) => $message->isOwnedBy($user));

        $data = $request->validate([
           'content' => ['required', 'string', 'max:65535']
        ]);

        $message->fill($data)->save();

        return new MessageResource($message);
    }

    public function destroy(Message $message): JsonResponse
    {
        Gate::allowIf(fn (User $user) => $message->isOwnedBy($user));

        $message->delete();
        Reply::where('message_id', $message->id)->delete();

        return response()->json(['message' => 'Message deleted successfully'], 200);
    }


}
