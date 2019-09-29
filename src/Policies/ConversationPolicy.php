<?php

namespace Envant\Chat\Policies;

use Envant\Chat\Models\Conversation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConversationPolicy
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
     * @param \Envant\Chat\Models\Conversation $conversation
     * @return mixed
     */
    public function view(?Model $user, Conversation $conversation): bool
    {
        return $conversation->isParticipant($user);
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
     * @param \Envant\Chat\Models\Conversation $conversation
     * @return mixed
     */
    public function update(?Model $user, Conversation $conversation): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \Illuminate\Database\Eloquent\Model|null $user
     * @param \Envant\Chat\Models\Conversation $conversation
     * @return mixed
     */
    public function delete(?Model $user, Conversation $conversation)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \Illuminate\Database\Eloquent\Model|null $user
     * @param \Envant\Chat\Models\Conversation $conversation
     * @return mixed
     */
    public function restore(?Model $user, Conversation $conversation)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \Illuminate\Database\Eloquent\Model|null $user
     * @param \Envant\Chat\Models\Conversation $conversation
     * @return mixed
     */
    public function forceDelete(?Model $user, Conversation $conversation)
    {
        return true;
    }
}
