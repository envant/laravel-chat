<?php

namespace Envant\Chat;

use Gate;
use Envant\Chat\Models\Message;
use Envant\Chat\Models\Conversation;
use Illuminate\Support\ServiceProvider;
use Envant\Chat\Observers\MessageObserver;
use Envant\Chat\Observers\ConversationObserver;

class ChatServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        $this->loadRoutes();

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }

        Message::observe(MessageObserver::class);
        Conversation::observe(ConversationObserver::class);

        if (config('chat.routes.policies.conversation.enabled') === true) {
            Gate::policy(Conversation::class, config('chat.routes.policies.conversation.class'));
        }

        if (config('chat.routes.policies.message.enabled') === true) {
            Gate::policy(Message::class, config('chat.routes.policies.message.class'));
        }
    }

    /**
     * Register routes
     *
     * @return void
     */
    public function loadRoutes()
    {
        if (config('chat.routes.enabled') === true) {
            $this->loadRoutesFrom(__DIR__ . '/routes.php');
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/chat.php', 'chat');
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/chat.php' => config_path('chat.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../migrations/' => database_path('migrations')
        ], 'migrations');
    }
}
