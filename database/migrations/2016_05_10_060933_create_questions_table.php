<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id')->unsigned();

            $table->integer('parent_id')->unsigned()->nullable();
            $table->foreign('parent_id')->references('id')->on('questions');

            $table->integer('sibling_order');
            $table->string('section');
            $table->string('input_type');
            $table->string('name');
            $table->string('subtext');
            $table->smallInteger('required')->default(0);
            $table->integer('dependent_question_id')->nullable();

            $table->integer('dependent_parent_option_id')->nullable();

            $table->unique(['parent_id','sibling_order']);
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
        Schema::drop('questions');
    }
}
