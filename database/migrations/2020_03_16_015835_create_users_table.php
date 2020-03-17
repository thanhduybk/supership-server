<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('id')->autoIncrement();
            $table->string('name', 100);
            $table->string('email', 100);
            $table->string('phone', 15);
            $table->string('password', 100);
            $table->string('shop');
            $table->string('referral_code', 50)->nullable()->default(null);

            $table->unique(["email"], 'users_email_uindex');

            $table->unique(["phone"], 'users_phone_uindex');
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
