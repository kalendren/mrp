<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFoIdToAssetsTable extends Migration
{
    public function up()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->unsignedBigInteger('fo_id')->nullable()->after('wo_id'); // Add Finished Order ID
            $table->foreign('fo_id')->references('id')->on('finished_orders')->onDelete('set null'); // Foreign key
        });
    }

    public function down()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropForeign(['fo_id']);
            $table->dropColumn('fo_id');
        });
    }
}
