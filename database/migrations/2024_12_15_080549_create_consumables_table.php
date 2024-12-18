<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsumablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumables', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('wo_id')->nullable(); // Link to Production Order
            $table->unsignedBigInteger('fo_id')->nullable(); // Link to Finished Order
            $table->string('type'); // Type of Consumable
            $table->integer('quantity'); // Quantity of Consumable
            $table->string('uom'); // Unit of Measure (e.g., kg, pcs, length)
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
        Schema::dropIfExists('consumables');
    }
}
