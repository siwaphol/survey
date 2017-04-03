<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSummaryToolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('summary_tools', function (Blueprint $table) {
            $table->integer('id');

            $table->json('table1')->nullable();
            $table->json('table2')->nullable();
            $table->json('table3')->nullable();
            $table->json('table4')->nullable();

            $table->string('table1_start_column');
            $table->string('table2_start_column');
            $table->string('table3_start_column');
            $table->string('table4_start_column');

            $table->string('input_file');
            $table->string('output_file');
            $table->string('input_sheet_name');
            $table->integer('start_row');
            $table->string('download_name');

            $table->string('tool_factor')->nullable();
            $table->string('season_factor')->nullable();
            $table->string('usage_factor')->nullable();
            $table->string('electric_power')->nullable();

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
        Schema::drop('summary_tools');
    }
}
