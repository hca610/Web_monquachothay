<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    public $title_list = ['report', 'chat'];
    public $status_list = ['hiden', 'unseen', 'seen'];

    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $title_list = ['report', 'chat'];
            $status_list = ['hiden', 'unseen', 'seen'];
            $table->id('message_id');
            // $table->string('title');
            $table->enum('title', $title_list);
            $table->string('detail');
            // $table->string('status');
            $table->enum('status', $status_list);
            $table->foreignId('sender_id');
            $table->foreignId('receiver_id');
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
