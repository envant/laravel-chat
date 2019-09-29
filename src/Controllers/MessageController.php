<?php

namespace Envant\Chat\Controllers;

use Envant\Chat\Chat;
use Envant\Chat\Models\Conversation;
use Envant\Chat\Models\Message;
use Envant\Chat\Requests\MessageRequest;
use Envant\Chat\Resources\MessageResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    use AuthorizesRequests;

    /** @var \Illuminate\Contracts\Auth\Authenticatable|null  */
    private $user;

    public function __construct()
    {
        $this->user = Auth::user();

        $this->authorizeResource(Message::class);
    }

    public function index(Conversation $conversation)
    {
        if (config('chat.routes.policies.conversation.enabled') === true) {
            $this->authorize('view', $conversation);
        }

        $messages = $conversation->messages()
            ->with('user')
            ->paginate();

        return MessageResource::collection($messages);
    }

    public function messageUser(MessageRequest $request, $userId)
    {
        $user = Chat::getAuthModel()
            ->where(Chat::getAuthModel()->getKeyName(), $userId)
            ->firstOrFail();

        $conversation = Conversation::getOrCreateBetweenUsers($this->user, $user);
        $message = $conversation->createMessage($request->validated());

        return new MessageResource($message);
    }

    public function store(MessageRequest $request, Conversation $conversation)
    {
        if (config('chat.routes.policies.conversation.enabled') === true) {
            $this->authorize('view', $conversation);
        }

        $message = $conversation->createMessage($request->validated());

        return new MessageResource($message);
    }

    public function update(MessageRequest $request, Conversation $conversation, Message $message)
    {
        $message->update($request->validated());

        return new MessageResource($message);
    }

    public function destroy(Conversation $conversation, Message $message)
    {
        $message->delete();

        response()->json([], Response::HTTP_NO_CONTENT);
    }
}
