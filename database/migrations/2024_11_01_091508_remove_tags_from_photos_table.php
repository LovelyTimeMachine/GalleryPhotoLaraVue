<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveTagsFromPhotosTable extends Migration
{
    public function up()
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->dropColumn('tags');
        });
    }

    public function down()
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->string('tags')->nullable(); // Sesuaikan dengan tipe kolom sebelumnya
        });
    }
}
