<?php

namespace Envant\Chat\Events;

use Envant\Chat\Models\Conversation;
use Illuminate\Queue\SerializesModels;

class ConversationUpdated
{
    use SerializesModels;

    /** @var \Envant\Chat\Models\Conversation  */
    public $conversation;

    /**
     * Create a new event instance.
     *
     * @param \Envant\Chat\Models\Conversation $conversation
     */
    public function __construct(Conversation $conversation)
    {
        $this->conversation = $conversation;
    }
}
