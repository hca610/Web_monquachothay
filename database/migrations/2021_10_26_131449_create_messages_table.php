<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    public static $type_list = ['report', 'chat'];
    public static $status_list = ['hidden', 'unseen', 'seen'];

    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id('message_id');
            $table->enum('type', $this::$type_list)->default('chat');
            $table->string('detail')->default("There is nothing in this message");
            $table->enum('status', $this::$status_list)->default('unseen');
            $table->foreignId('sender_id')->default(1);
            $table->foreignId('receiver_id')->default(1);
            $table->timestamps();

            // Foreign key
            $table->foreign('sender_id')
                ->references('user_id')->on('users');

            $table->foreign('receiver_id')
                ->references('user_id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
