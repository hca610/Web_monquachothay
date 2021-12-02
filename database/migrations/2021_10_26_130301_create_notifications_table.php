<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    public $title_list = ['warning', 'special'];
    public $status_list = ['hiden', 'unseen', 'seen'];

    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $title_list = ['normal', 'warning', 'special'];
            $status_list = ['hidden', 'unseen', 'seen'];
            $table->id('notification_id');
            $table->enum('title', $title_list)->default('normal');
            $table->string('detail')->default("There is nothing in this notification");
            $table->enum('status', $status_list)->default('unseen');
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
