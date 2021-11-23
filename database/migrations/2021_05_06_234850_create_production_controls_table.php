<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionControlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_controls', function (Blueprint $table) {
            $table->id();
            $table->integer('growing_crop_id');
            $table->dateTime('work_date');
            $table->string('work_content_id')->nullable();
            $table->string('seed_id')->nullable();
            $table->string('seed_volume')->nullable();
            $table->string('equipments_id')->nullable();
            $table->string('equip_method_id')->nullable();
            $table->string('others')->nullable();
            $table->string('after_harvest_id')->nullable();
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
        Schema::dropIfExists('production_controls');
    }
}
