<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployersTable extends Migration
{
    public function up()
    {
        Schema::create('employers', function (Blueprint $table) {
            $table->id('employer_id');
            $table->foreignId('user_id');
            $table->string('about_us');
            $table->string('image_link');
            $table->integer('num_employee');
            $table->foreignId('category_id');

            // Foreign key
            $table->foreign('user_id')
                ->references('user_id')->on('users');
            $table->foreign('category_id')
                ->references('category_id')->on('categories');
        });
    }

    public function down()
    {
        Schema::dropIfExists('employers');
    }
}
