<?php

namespace Envant\Chat\Models;

use Ankurk91\Eloquent\BelongsToOne;
use Envant\Chat\Chat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class Conversation extends Model
{
    use BelongsToOne;

    /** @var array */
    protected $fillable = [
        'name',
        'model_id',
        'model_type',
    ];

    /** @var array */
    protected $hidden = [
        'model_id',
        'model_type',
        'type',
    ];

    public const TYPE_PRIVATE = 'private';
    public const TYPE_PUBLIC = 'public';

    /**
     * Override default model name
     *
     * @return string
     */
    public function getTable(): string
    {
        return config('chat.conversations_table');
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // Set conversation type depending on related model type
        static::creating(function ($model) {
            $model->type = $model->model_type != Chat::getAuthModelName()
                ? static::TYPE_PUBLIC
                : static::TYPE_PRIVATE;
        });

        // Order by latest message
        static::addGlobalScope('order', function (Builder $query) {
            /*
            $query->orderBy(function ($query) {
                $query->select('created_at')
                    ->from(Message::getModel()->getTable())
                    ->whereColumn('conversation_id', self::getModel()->getTable() . '.id')
                    ->latest()
                    ->limit(1)
                    ->get();
            });
            */
        });
    }

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     */

    /**
     * Related model
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Participants
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(
            Chat::getAuthModelName(),
            config('chat.participants_table'),
            'conversation_id',
            'user_id'
        );
    }

    /**
     * Companion of private conversation
     *
     * @return \Ankurk91\Eloquent\BelongsToOne
     */
    public function companion(): \Ankurk91\Eloquent\Relations\BelongsToOne
    {
        return $this->belongsToOne(
            Chat::getAuthModel(),
            config('chat.participants_table'),
            'conversation_id',
            'user_id',
        )->where('id', '<>', Auth::id());
    }

    /**
     * Messages
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(config('chat.message_model'));
    }

    /**
     * Last message
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lastMessage(): HasOne
    {
        return $this->hasOne(config('chat.message_model'))->latest();
    }

    /**
     * Unread messages
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function unreadMessages(): HasMany
    {
        return $this->messages()
            ->where('user_id', '<>', Auth::id())
            ->whereDoesntHave('readers', function (Builder $query) {
                $query->where('id', Auth::id());
            });
    }

    /*
     |--------------------------------------------------------------------------
     | Methods
     |--------------------------------------------------------------------------
     */

    public function createMessage(array $attributes): Message
    {
        $attributes['user_id'] = Auth::id();

        $message = $this->messages()->create($attributes);

        return $message;
    }

    /**
     * Mark all unread messages as read
     *
     * @return void
     */
    public function markAllAsRead()
    {
        $unreadMessagesIds = $this->unreadMessages()
            ->pluck('id');

        return Auth::user()->readMessages()->syncWithoutDetaching($unreadMessagesIds);
    }

    /**
     * Add participant
     *
     * @param Model|null $user
     * @return void
     */
    public function addParticipant(?Model $user)
    {
        $this->participants()->attach($user);
    }

    /**
     * Remove participant
     *
     * @param Model|null $user
     * @return void
     */
    public function removeParticipant(?Model $user)
    {
        $this->participants()->dettach($user);
    }

    /**
     * Get or create a conversation between given users
     *
     * @param Model|null $sender
     * @param Model|null $recipient
     * @return Conversation
     */
    public static function getOrCreateBetweenUsers(?Model $sender, ?Model $recipient): Conversation
    {
        $conversation = static::getBetweenUsers($sender, $recipient)
            ?? static::createBetweenUsers($sender, $recipient);

        return $conversation;
    }

    /**
     * Get a conversation between given users
     *
     * @param Model|null $sender
     * @param Model|null $recipient
     * @return Conversation|null
     */
    public static function getBetweenUsers(?Model $sender, ?Model $recipient): ?Conversation
    {
        $conversation = Conversation::where('type', static::TYPE_PRIVATE)
            ->whereHas('participants', function (Builder $query) use ($sender) {
                $query->where('id', $sender->getKey());
            })
            ->whereHas('participants', function (Builder $query) use ($recipient) {
                $query->where('id', $recipient->getKey());
            })
            ->first();

        return $conversation;
    }

    /**
     * Create a conversation between given users
     *
     * @param Model|null $sender
     * @param Model|null $recipient
     * @return Conversation
     */
    public static function createBetweenUsers(?Model $sender, ?Model $recipient): Conversation
    {
        $conversation = $sender->ownConversations()->create([]);
        $conversation->participants()->sync([$sender->id, $recipient->id]);

        return $conversation;
    }

    /**
     * Check if conversation has given user as a "participant"
     *
     * @param Model|null $user
     * @return boolean
     */
    public function isParticipant(?Model $user): bool
    {
        return $this->participants()
            ->where(Chat::getAuthModel()->getKeyName(), $user->getKey())
            ->exists();
    }
}
