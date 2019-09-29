<?php

namespace Envant\Chat\Controllers;

use Envant\Chat\Models\Conversation;
use Envant\Chat\Requests\Conversation\CreateRequest;
use Envant\Chat\Requests\Conversation\UpdateRequest;
use Envant\Chat\Resources\ConversationResource;
use Envant\Helpers\ModelMapper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{
    use AuthorizesRequests;
    /** @var \Illuminate\Contracts\Auth\Authenticatable|null  */
    private $user;

    public function __construct()
    {
        $this->user = Auth::user();

        $this->authorizeResource(Conversation::class);
    }

    public function index()
    {
        $conversations = $this->user->conversations()
            ->withCount('unreadMessages')
            ->paginate();

        return ConversationResource::collection($conversations);
    }

    public function show(Conversation $conversation)
    {
        $conversation->load('participants');
        $conversation->loadCount('unreadMessages');

        return new ConversationResource($conversation);
    }

    public function store(CreateRequest $request)
    {
        $model = ModelMapper::getEntity($request->model_type, $request->model_id);
        $conversation = $model->conversations()->create($request->validated());

        return new ConversationResource($conversation);
    }

    public function update(UpdateRequest $request, Conversation $conversation)
    {
        $conversation->update($request->validated());

        return new ConversationResource($conversation);
    }

    public function readMessages(Conversation $conversation)
    {
        $conversation->markAllAsRead();

        return response()->json([
            'success' => true,
        ]);
    }

    public function destroy(Conversation $conversation)
    {
        $conversation->delete();

        response()->json([], Response::HTTP_NO_CONTENT);
    }
}
