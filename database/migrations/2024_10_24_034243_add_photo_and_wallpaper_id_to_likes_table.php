<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhotoAndWallpaperIdToLikesTable extends Migration
{
    public function up()
    {
        Schema::table('likes', function (Blueprint $table) {
            // Check if photo_id already exists before adding it
            if (!Schema::hasColumn('likes', 'photo_id')) {
                $table->foreignId('photo_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            }

            // Check if wallpaper_id already exists before adding it
            if (!Schema::hasColumn('likes', 'wallpaper_id')) {
                $table->foreignId('wallpaper_id')->nullable()->after('photo_id')->constrained()->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('likes', function (Blueprint $table) {
            // Drop foreign keys before dropping columns
            $table->dropForeign(['photo_id']);
            $table->dropForeign(['wallpaper_id']);
            
            // Drop the columns
            $table->dropColumn('photo_id');
            $table->dropColumn('wallpaper_id');
        });
    }
}
