<?php

namespace Envant\Chat\Policies;

use Envant\Chat\Models\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \Illuminate\Database\Eloquent\Model|null $user
     * @return mixed
     */
    public function viewAny(?Model $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \Illuminate\Database\Eloquent\Model|null $user
     * @param \Envant\Chat\Models\Message $message
     * @return mixed
     */
    public function view(?Model $user, Message $message): bool
    {
        return $message->user_id === $user->id
            || $message->conversation->isParticipant($user);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \Illuminate\Database\Eloquent\Model|null $user
     * @return mixed
     */
    public function create(?Model $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \Illuminate\Database\Eloquent\Model|null $user
     * @param \Envant\Chat\Models\Message $message
     * @return mixed
     */
    public function update(?Model $user, Message $message): bool
    {
        return $message->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \Illuminate\Database\Eloquent\Model|null $user
     * @param \Envant\Chat\Models\Message $message
     * @return mixed
     */
    public function delete(?Model $user, Message $message)
    {
        return $message->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \Illuminate\Database\Eloquent\Model|null $user
     * @param \Envant\Chat\Models\Message $message
     * @return mixed
     */
    public function restore(?Model $user, Message $message)
    {
        return $message->user_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \Illuminate\Database\Eloquent\Model|null $user
     * @param \Envant\Chat\Models\Message $message
     * @return mixed
     */
    public function forceDelete(?Model $user, Message $message)
    {
        return $message->user_id === $user->id;
    }
}
