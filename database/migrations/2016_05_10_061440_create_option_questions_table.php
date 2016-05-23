<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_questions', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();

            $table->bigInteger('question_id')->unsigned();
            $table->foreign('question_id')->references('id')->on('questions');
            $table->bigInteger('option_id')->unsigned();
            $table->foreign('option_id')->references('id')->on('options');
            $table->integer('order');

            $table->unique(['question_id','order']);
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
        Schema::drop('option_questions');
    }
}
