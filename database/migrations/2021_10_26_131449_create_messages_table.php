<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id('message_id');
            $table->string('title');
            $table->string('detail');
            $table->string('status');
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
