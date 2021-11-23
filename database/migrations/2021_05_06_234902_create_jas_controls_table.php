<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJasControlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jas_controls', function (Blueprint $table) {
            $table->id();
            $table->integer('growing_crop_id');
            $table->dateTime('work_date');
            $table->integer('packaging_unit')->nullable();
            $table->integer('consignments_count')->nullable();
            $table->integer('weight')->nullable();
            $table->integer('destination_id')->nullable();
            $table->integer('used_count')->nullable();
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
        Schema::dropIfExists('jas_controls');
    }
}
