<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobSeekersTable extends Migration
{
    public function up()
    {
        Schema::create('job_seekers', function (Blueprint $table) {
            $table->id('job_seeker_id');
            $table->foreignId('user_id');
            $table->date('birthday')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('qualification')->nullable();
            $table->string('work_experience')->nullable();
            $table->string('education')->nullable();
            $table->string('skill')->nullable();

            //Foreign key
            $table->foreign('user_id')
                ->references('user_id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('job_seekers');
    }
}
