<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrowingCropsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('growing_crops', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('variety_id');
            $table->dateTime('starting_date');
            $table->integer('farm_id');
            $table->string('cultivation_area')->nullable();
            $table->integer('harvest_flg')->default('0');
            $table->integer('del_flg')->default('0');
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
        Schema::dropIfExists('growing_crops');
    }
}
