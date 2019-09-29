<?php

return [
    'conversations_table' => 'chat_conversations',
    'conversation_model' => Envant\Chat\Models\Conversation::class,
    'messages_table' => 'chat_messages',
    'message_model' => Envant\Chat\Models\Message::class,
    'participants_table' => 'chat_participants',
    'read_messages_table' => 'chat_read_messages',
    'user_model' => null,
    'name_user_attribute' => 'full_name',
    'routes' => [
        'enabled' => true,
        'conversation_controller' => Envant\Chat\Controllers\ConversationController::class,
        'message_controller' => Envant\Chat\Controllers\MessageController::class,
        'middleware' => [
            'api', 'auth:api'
        ],
        'prefix' => 'api',
        'policies' => [
            'conversation' => [
                'enabled' => true,
                'class' => Envant\Chat\Policies\ConversationPolicy::class,
            ],
            'message' => [
                'enabled' => true,
                'class' => Envant\Chat\Policies\MessagePolicy::class,
            ],
        ]
    ],
];
