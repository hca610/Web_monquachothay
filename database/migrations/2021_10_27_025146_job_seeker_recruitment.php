<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class JobSeekerRecruitment extends Migration
{
    public function up()
    {
        Schema::create('job_seeker_recruitment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('job_seeker_id');
            $table->foreignId('recruitment_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('type', ['following', 'working', 'quitted']);

            // Foreign key
            $table->foreign('job_seeker_id')
                ->references('job_seeker_id')->on('job_seekers');

            $table->foreign('recruitment_id')
                ->references('recruitment_id')->on('recruitments');
        });
    }

    public function down()
    {
        //
    }
}
