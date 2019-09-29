<?php

namespace Envant\Chat;

use Envant\Chat\Models\Conversation;
use Envant\Chat\Models\Message;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

trait HasChat
{
    protected static function bootHasChat()
    {
        // boot trait
    }

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     */

    /**
     * Assigned conversations
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function ownConversations(): MorphMany
    {
        return $this->morphMany(config('chat.conversation_model'), 'model');
    }

    /**
     * Assigned conversations
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(
            config('chat.conversation_model'),
            config('chat.participants_table'),
            'user_id',
            'conversation_id'
        );
    }

    /**
     * Viewed messages
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function readMessages(): BelongsToMany
    {
        return $this->belongsToMany(
            config('chat.message_model'),
            config('chat.read_messages_table'),
            'user_id',
            'message_id'
        );
    }

    /**
     * Unread messages
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    /*
    public function unreadMessages(): Builder
    {
        return Message::whereHas('conversation', function ($query) {
            $query->whereHas('participants', function ($query) {
                $query->where('user_id', '=', Auth::id());
            });
        })
            ->whereDoesntHave('readers', function ($query) {
                $query->where('id', Auth::id());
            });
    }
    */
}
