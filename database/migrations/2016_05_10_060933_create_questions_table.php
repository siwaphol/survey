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
            $table->bigInteger('id')->unsigned();

            $table->bigInteger('parent_id')->unsigned()->nullable();
            $table->foreign('parent_id')->references('id')->on('questions');

            $table->integer('sibling_order');
            $table->string('section');
            $table->string('sub_section');
            $table->string('input_type');
            $table->string('text');
            $table->string('unit_of_measure')->nullable();

            $table->smallInteger('required')->default(0);
//            $table->integer('dependent_question_id')->nullable();

            $table->integer('dependent_parent_option_id')->nullable();

            $table->unique(['parent_id','sibling_order']);
            $table->primary('id');
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
