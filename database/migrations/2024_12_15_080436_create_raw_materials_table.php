<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRawMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('wo_id')->nullable(); // Link to Production Order
            $table->unsignedBigInteger('fo_id')->nullable(); // Link to Finished Order
            $table->string('grade'); // Raw Material Grade
            $table->string('size'); // Raw Material Size
            $table->string('length'); // Raw Material Length
            $table->string('hn_bar_tno'); // Heat Number / Bar Number / TNO
            $table->timestamps(); // Created at / Updated at

            // Foreign Keys
            $table->foreign('wo_id')->references('id')->on('production_orders')->onDelete('set null');
            $table->foreign('fo_id')->references('id')->on('finished_orders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('raw_materials');
    }
}
