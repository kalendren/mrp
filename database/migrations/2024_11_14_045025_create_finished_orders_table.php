<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('finished_orders', function (Blueprint $table) {
        $table->id();
        $table->string('wo_number')->unique();
        $table->string('so_number');
        $table->string('cust_po_number')->nullable();
        $table->string('customer');
        $table->date('order_date');
        $table->date('required_date');
        $table->string('job_scope');
        $table->string('part_description');
        $table->string('drawing_no')->nullable();
        $table->integer('lot_size');
        $table->timestamp('date_closed')->nullable();
        $table->string('closed_by')->nullable();
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
        Schema::dropIfExists('finished_orders');
    }
};
