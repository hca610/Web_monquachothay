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

            // Foreign key
            $table->foreign('user_id')
                ->references('user_id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('employers');
    }
}
