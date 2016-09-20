<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name_en')->nullable();
            $table->string('name_th');
            $table->string('code')->nullable();
            $table->double('value')->default(0);
            $table->string('unit_of_measure')->nullable();

            $table->integer('group_id')->unsigned()->nullable();
            $table->foreign('group_id')->references('id')->on('setting_groups');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('settings');
    }
}
