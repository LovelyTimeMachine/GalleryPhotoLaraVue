<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->dropColumn('alt'); // Drop the 'alt' column
        });
    }
    
    public function down()
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->string('alt')->nullable(); // Re-add 'alt' column if needed in the future
        });
    }
    
};
