<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('id')->autoIncrement();
            $table->string('name', 50);
            $table->bigInteger('province_id');

            $table->index(["province_id"], 'district_province_id_fk');

            $table->unique(["name"], 'district_name_uindex');


            $table->foreign('province_id', 'district_province_id_fk')
                ->references('id')->on('provinces')
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
        Schema::dropIfExists('districts');
    }
}
