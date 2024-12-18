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
    Schema::table('operations', function (Blueprint $table) {
        $table->unsignedBigInteger('finished_order_id')->nullable()->after('id'); // Adjust after which column it should be placed
        $table->foreign('finished_order_id')->references('id')->on('finished_orders')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('operations', function (Blueprint $table) {
        $table->dropForeign(['finished_order_id']);
        $table->dropColumn('finished_order_id');
    });
}
};
