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
            $table->string('category');
            $table->foreignId('employer_id');
            $table->integer('min_salary');
            $table->string('job_name');
            $table->text('detail');
            $table->enum('status', ['opening', 'closed'])->default('opening');
            $table->text('requirement');
            $table->string('address');
            $table->timestamps();

            $table->foreign('employer_id')
                ->references('employer_id')->on('employers');

        });
    }

    public function down()
    {
        Schema::dropIfExists('recruitments');
    }
}
