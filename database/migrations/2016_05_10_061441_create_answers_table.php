<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            
            $table->bigInteger('main_id')->unsigned();
            $table->foreign('main_id')->references('id')->on('mains');

            $table->bigInteger('option_question_id')->unsigned();
            $table->foreign('option_question_id')->references('id')->on('option_questions');
            $table->integer('question_id')->unsigned();
            $table->foreign('question_id')->references('id')->on('questions');

            $table->float('answer_numeric')->nullable();
            $table->string('answer_text')->nullable();
            $table->smallInteger('answer_yn')->nullable();
            $table->string('other_text')->nullable();
            
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
        Schema::drop('answers');
    }
}