<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public static $status_list = ['hidden', 'unseen', 'seen'];

    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('notification_id');
            $table->string('title')->default("A short description about this notification");
            $table->string('detail')->default("There is nothing in this notification");
            $table->enum('status', $this::$status_list)->default('unseen');
            $table->foreignId('receiver_id')->default(1);
            $table->timestamps();

            // Foreign key
            $table->foreign('receiver_id')
                ->references('user_id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
