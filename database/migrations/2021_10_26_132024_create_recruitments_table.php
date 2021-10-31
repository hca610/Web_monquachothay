<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitments', function (Blueprint $table) {
            $table->id('recruitment_id');
            $table->unsignedBigInteger('category_id');
            $table->integer('min_salary');
            $table->string('job_name');
            $table->string('detail');
            $table->string('status');
            $table->string('requirement');
            $table->string('address');
            $table->integer('employer_id');
            $table->timestamps();


            $table->foreign('category_id')
                ->references('category_id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recruitments');
    }
}
