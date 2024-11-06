<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAlbumIdInWallpapersTable extends Migration
{
    public function up()
    {
        Schema::table('wallpapers', function (Blueprint $table) {
            $table->unsignedBigInteger('album_id')->nullable()->change(); // Make album_id nullable
        });
    }

    public function down()
    {
        Schema::table('wallpapers', function (Blueprint $table) {
            $table->unsignedBigInteger('album_id')->nullable(false)->change(); // Revert it back if needed
        });
    }
}
