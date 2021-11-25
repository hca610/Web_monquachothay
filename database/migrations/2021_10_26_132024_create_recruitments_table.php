<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentsTable extends Migration
{
    public function up()
    {
        Schema::create('recruitments', function (Blueprint $table) {
            $table->id('recruitment_id');
            $table->foreignId('category_id');
            $table->foreignId('employer_id');
            $table->integer('min_salary');
            $table->string('job_name');
            $table->string('detail');
            $table->enum('status', ['opening', 'closed'])->default('opening');
            $table->string('requirement');
            $table->string('address');
            $table->timestamps();

            $table->foreign('category_id')
                ->references('category_id')->on('categories');
            $table->foreign('employer_id')
                ->references('employer_id')->on('employers');

        });
    }

    public function down()
    {
        Schema::dropIfExists('recruitments');
    }
}
