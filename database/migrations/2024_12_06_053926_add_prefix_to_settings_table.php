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
    Schema::table('settings', function (Blueprint $table) {
        $table->string('Prefix')->nullable();  // Add the Prefix column
    });
}

public function down()
{
    Schema::table('settings', function (Blueprint $table) {
        $table->dropColumn('Prefix');  // Drop the Prefix column if rolled back
    });
}
};
