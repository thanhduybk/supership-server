<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdjustUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('shop');
            $table->string('name')->change();
            $table->string('phone')->unique();
            $table->string('password')->change();
            $table->string('referral_code')->nullable();
            $table->dateTime('created_at')->useCurrent()->change();
            $table->dateTime('updated_at')->after('created_at')->change();

            $table->dropColumn(['email_verified_at', 'remember_token']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {

        });
    }
}
