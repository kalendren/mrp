<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('production_orders', function (Blueprint $table) {
            $table->id();
            $table->string('wo_number')->unique();
            $table->string('so_number');
            $table->string('cust_po_number');
            $table->string('customer');
            $table->date('order_date');
            $table->date('required_date');
            $table->string('job_scope');
            $table->string('part_description');
            $table->string('drawing_no')->nullable();
            $table->string('asset_no')->nullable();
            $table->integer('lot_size');
            $table->integer('so_sequence')->nullable(); // Make nullable if needed
            $table->timestamps();

            // Optional: Add an index for performance
            $table->index('so_number');
        });
    }

    public function down()
    {
        Schema::dropIfExists('production_orders');
    }
}
