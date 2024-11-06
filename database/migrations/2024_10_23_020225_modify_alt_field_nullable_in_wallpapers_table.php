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
    Schema::table('wallpapers', function (Blueprint $table) {
        $table->string('alt')->nullable()->change(); // Make 'alt' column nullable
    });
}

public function down()
{
    Schema::table('wallpapers', function (Blueprint $table) {
        $table->string('alt')->nullable(false)->change(); // Revert 'alt' column to non-nullable if needed
    });
}

};
