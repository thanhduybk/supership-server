<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'orders';
    /**
     * Run the migrations.
     * @table orders
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('id')->autoIncrement();
            $table->string('product', 100);
            $table->string('receiver', 100);
            $table->string('address', 100);
            $table->bigInteger('repository_id');
            $table->bigInteger('ward_id');
            $table->bigInteger('sender_id')->nullable()->default(0);
            $table->bigInteger('money_taking');
            $table->timestamps();

            $table->index(["repository_id"], 'orders_repositories_id_fk');

            $table->index(["sender_id"], 'orders_users_id_fk');

            $table->index(["ward_id"], 'orders_wards_id_fk');


            $table->foreign('repository_id', 'orders_repositories_id_fk')
                ->references('id')->on('repositories')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('sender_id', 'orders_users_id_fk')
                ->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('ward_id', 'orders_wards_id_fk')
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
        Schema::dropIfExists($this->tableName);
    }
}
