<?php

namespace Envant\Chat;

use Illuminate\Database\Eloquent\Model;
use Exception;

class Chat
{
    /**
     * Get auth model name
     *
     * @return string
     * @throws Exception
     */
    public static function getAuthModelName(): string
    {
        if (config('chat.user_model')) {
            return config('chat.user_model');
        }

        if (!is_null(config('auth.providers.users.model'))) {
            return config('auth.providers.users.model');
        }

        throw new Exception('Could not determine the user model name.');
    }

    /**
     * Get auth model instance
     *
     * @return Model
     */
    public static function getAuthModel(): Model
    {
        $modelName = static::getAuthModelName();

        return new $modelName();
    }
}
