<?php

namespace Envant\Chat\Models;

use Envant\Chat\Chat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Message extends Model
{
    /** @var array */
    protected $fillable = [
        'body',
        'user_id',
    ];

    /** @var array */
    protected $hidden = [];

    /**
     * Override default model name
     *
     * @return string
     */
    public function getTable(): string
    {
        return config('chat.messages_table');
    }

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     */

    /**
     * Sender
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Chat::getAuthModelName(), 'user_id');
    }

    /**
     * Conversation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(config('chat.conversation_model'), 'conversation_id');
    }

    /**
     * People that read the message
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function readers(): BelongsToMany
    {
        return $this->belongsToMany(
            Chat::getAuthModelName(),
            config('chat.read_messages_table'),
            'message_id',
            'user_id'
        );
    }
}
