<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('tees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->string('tee_name',255);
            $table->string('gender',10)->nullable();
            $table->tinyInteger('par')->nullable();
            $table->float('rating')->nullable();
            $table->smallInteger('slope')->nullable();
            $table->float('bogey_rating')->nullable();
            $table->float('front_rating')->nullable();
            $table->smallInteger('front_slope')->nullable();
            $table->float('back_rating')->nullable();
            $table->smallInteger('back_slope')->nullable();
            $table->timestamps();
        });

        /*Schema::table('tees', function($table) {
         $table->foreign('course_id')->references('id')->on('courses');
       });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tees');
    }
}
