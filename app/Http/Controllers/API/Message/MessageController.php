<?php

namespace App\Http\Controllers\API\Message;

use App\Enums\MessageTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Message\IndexMessageRequest;
use App\Http\Requests\Message\MessageRequest;
use App\Http\Resources\Message\MessageResource;
use App\Models\Message;
use App\Models\PrivateMessageRecipient;
use App\Models\Reply;
use App\Models\User;
use App\Services\Filters\Message\FilterManager;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class MessageController extends Controller
{
    public function index(IndexMessageRequest $request): AnonymousResourceCollection
    {
        $messageFilter = new FilterManager();

        if (Auth::check()) {
            $user = Auth::user();
            $messages = Message::query()->get();
            $messages = $messages->filter(function ($message) use ($user) {
                return $message->type !== MessageTypeEnum::PRIVATE || $message->user_id === $user->id || $message->privateMessageRecipients->first()->user_id === $user->id;
            });
            $messages = $messageFilter->apply($messages->toQuery(), $request->all())->get();
        } else {
            $messages = $messageFilter->apply(Message::where('type', MessageTypeEnum::ALL), $request->all())->get();
        }

        return MessageResource::collection($messages);
    }

    public function show(Message $message): MessageResource
    {
        if (Auth::check()) {
            if($message->type === MessageTypeEnum::PRIVATE){
                $user = Auth::user();
                $recipient_id = $message->privateMessageRecipients()->first()->user_id;
                if($recipient_id === $user->id){
                    return new MessageResource($message);
                }
                if($message->user_id === $user->id){
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
        $message->save();

        if($data['type'] === MessageTypeEnum::PRIVATE){
            $privateMessageRecipient = new PrivateMessageRecipient();
            $privateMessageRecipient->user_id = $data['recipient_id'];
            $privateMessageRecipient->message_id = $message->id;
            $privateMessageRecipient->save();
        }

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

    public function destroy(Message $message): void
    {
        Gate::allowIf(fn (User $user) => $message->isOwnedBy($user));

        $message->delete();
        Reply::where('message_id', $message->id)->delete();
    }


}
