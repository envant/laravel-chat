<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Envant\Chat\Chat;

class CreateChatTables extends Migration
{
    /** @var \Illuminate\Database\Eloquent\Model $userModel */
    protected $userModel;

    public function __construct()
    {
        /** @var string $userClass */
        $userClass = Chat::getAuthModelName();

        $this->userModel = new $userClass();
    }

    /**
     * Run the migrations.
     * @throws \Exception
     */
    public function up()
    {
        // Conversations
        Schema::create(config('chat.conversations_table'), function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->morphs('model');
            $table->string('type')->default('private');
            $table->string('name')->nullable();

            $table->timestamps();
        });

        // Participants
        Schema::create(config('chat.participants_table'), function (Blueprint $table) {
            $table->unsignedBigInteger('conversation_id');
            $table->foreign('conversation_id')
                ->references('id')
                ->on(config('chat.conversations_table'))
                ->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references($this->userModel->getKeyName())
                ->on($this->userModel->getTable())
                ->onDelete('cascade');

            $table->primary(['conversation_id', 'user_id']);
        });

        // Messages
        Schema::create(config('chat.messages_table'), function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('conversation_id');
            $table->foreign('conversation_id')
                ->references('id')
                ->on(config('chat.conversations_table'))
                ->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references($this->userModel->getKeyName())
                ->on($this->userModel->getTable())
                ->onDelete('cascade');

            $table->text('body')->nullable();

            $table->timestamps();
        });

        // Read messages
        Schema::create(config('chat.read_messages_table'), function (Blueprint $table) {
            $table->unsignedBigInteger('message_id');
            $table->foreign('message_id')
                ->references('id')
                ->on(config('chat.messages_table'))
                ->onDelete('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references($this->userModel->getKeyName())
                ->on($this->userModel->getTable())
                ->onDelete('cascade');

            $table->primary(['message_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists(config('chat.participants_table'));
        Schema::dropIfExists(config('chat.read_messages_table'));
        Schema::dropIfExists(config('chat.messages_table'));
        Schema::dropIfExists(config('chat.conversations_table'));
    }
}
