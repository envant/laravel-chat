<?php

Route::prefix(config('chat.routes.prefix'))->middleware(config('chat.routes.middleware'))->group(function () {
    Route::prefix('chat')->group(function () {
        Route::resource('conversations', config('chat.routes.conversation_controller'));
        
        Route::post('conversations/{conversation}/read', config('chat.routes.conversation_controller') . '@readMessages');
        Route::resource('conversations.messages', config('chat.routes.message_controller'))
            ->only(['index', 'store', 'update', 'destroy']);

        // Start a conversation with user
        Route::post('users/{user}', config('chat.routes.message_controller') . '@messageUser');
    });
});
