<?php

namespace Envant\Chat\Observers;

use Envant\Chat\Models\Conversation;
use Illuminate\Support\Facades\Event;
use Envant\Chat\Events\ConversationCreated;
use Envant\Chat\Events\ConversationUpdated;
use Envant\Chat\Events\ConversationDeleted;

class ConversationObserver
{
    /**
     * Handle the conversation "created" event
     *
     * @param \Envant\Conversations\Models\Conversation $conversation
     * @return void
     */
    public function created(Conversation $conversation)
    {
        Event::dispatch(new ConversationCreated($conversation));
    }

    /**
     * Handle the conversation "updated" event
     *
     * @param \Envant\Conversations\Models\Conversation $conversation
     * @return void
     */
    public function updated(Conversation $conversation)
    {
        Event::dispatch(new ConversationUpdated($conversation));
    }

    /**
     * Handle the conversation "deleted" event
     *
     * @param \Envant\Conversations\Models\Conversation $conversation
     * @return void
     */
    public function deleted(Conversation $conversation)
    {
        Event::dispatch(new ConversationDeleted($conversation));
    }
}
