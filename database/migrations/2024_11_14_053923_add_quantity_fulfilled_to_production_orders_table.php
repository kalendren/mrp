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
        Schema::table('production_orders', function (Blueprint $table) {
            // Add the `quantity_fulfilled` column (with default value of 0)
            $table->integer('quantity_fulfilled')->default(0); // Adjust type if necessary
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('production_orders', function (Blueprint $table) {
            // Remove the `quantity_fulfilled` column if the migration is rolled back
            $table->dropColumn('quantity_fulfilled');
        });
    }
};
