<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('username');
            $table->string('password');
            $table->string('name');
            $table->string('phonenumber')->unique();
            $table->string('email')->unique();
            $table->string('address');
            $table->enum('role', ['jobseeker', 'employer']);
            $table->enum('status', ['active', 'banned'])->default('active');
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
