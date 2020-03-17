<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepositoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repositories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('id')->autoIncrement();
            $table->string('phone', 15);
            $table->string('contact', 100);
            $table->string('address', 50);
            $table->bigInteger('ward_id');
            $table->bigInteger('owner_id');

            $table->index(["ward_id"], 'repository_ward_id_fk');

            $table->index(["owner_id"], 'repositories_users_id_fk');


            $table->foreign('owner_id', 'repositories_users_id_fk')
                ->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('ward_id', 'repository_ward_id_fk')
                ->references('id')->on('wards')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repositories');
    }
}
