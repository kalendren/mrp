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
        // Alter the 'coc_statement' column to 'TEXT' type (for longer text)
        Schema::table('settings', function (Blueprint $table) {
            $table->text('coc_statement')->change(); // Change column to TEXT
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revert the 'coc_statement' column back to a string (varchar) with a length of 255
        Schema::table('settings', function (Blueprint $table) {
            $table->string('coc_statement', 255)->change(); // Revert back to VARCHAR(255)
        });
    }
};
