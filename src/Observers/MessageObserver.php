<?php

namespace Envant\Chat\Observers;

use Envant\Chat\Models\Message;
use Illuminate\Support\Facades\Event;
use Envant\Chat\Events\MessageSent;
use Envant\Chat\Events\MessageUpdated;
use Envant\Chat\Events\MessageDeleted;

class MessageObserver
{
    /**
     * Handle the message "created" event
     *
     * @param \Envant\Messages\Models\Message $message
     * @return void
     */
    public function created(Message $message)
    {
        Event::dispatch(new MessageSent($message));
    }

    /**
     * Handle the message "updated" event
     *
     * @param \Envant\Messages\Models\Message $message
     * @return void
     */
    public function updated(Message $message)
    {
        Event::dispatch(new MessageUpdated($message));
    }

    /**
     * Handle the message "deleted" event
     *
     * @param \Envant\Messages\Models\Message $message
     * @return void
     */
    public function deleted(Message $message)
    {
        Event::dispatch(new MessageDeleted($message));
    }
}
