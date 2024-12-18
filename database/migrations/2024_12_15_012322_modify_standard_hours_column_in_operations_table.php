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
        $table->decimal('standard_hours', 8, 2)->change();
    });
}

public function down()
{
    Schema::table('operations', function (Blueprint $table) {
        $table->integer('standard_hours')->change(); // Revert back to integer if needed
    });
}

};
