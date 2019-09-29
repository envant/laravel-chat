<?php

namespace Envant\Chat\Events;

use Envant\Chat\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcast
{
    use SerializesModels,
        Dispatchable,
        InteractsWithSockets;

    /** @var \Envant\Chat\Models\Message  */
    public $message;

    /**
     * Create a new event instance.
     *
     * @param \Envant\Chat\Models\Message $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return new Channel('chat-conversation.' . $this->message->conversation->id);
    }
}
