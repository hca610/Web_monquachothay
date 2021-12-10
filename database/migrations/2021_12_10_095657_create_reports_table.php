<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    public static $status_list = ['hidden', 'unseen', 'seen'];
    
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id('report_id');
            $table->string('detail')->default("There is nothing in this report");
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
        Schema::dropIfExists('reports');
    }
}
