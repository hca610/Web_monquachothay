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
            $title_list = ['warning', 'special'];
            $status_list = ['hiden', 'unseen', 'seen'];
            $table->id('notification_id');
            // $table->string('title');
            $table->enum('title', $title_list);
            $table->string('detail');
            // $table->string('status');
            $table->enum('status', $status_list);
            $table->foreignId('receiver_id');
            $table->timestamps();

            $table->foreign('receiver_id')
                ->references('user_id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
