<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhotosTable extends Migration
{
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul photo
            $table->string('description'); // Deskripsi photo
            $table->string('tags'); // Tags untuk pencarian atau kategorisasi
            $table->string('url'); // URL lokasi photo
            $table->string('alt'); // Teks alternatif untuk SEO dan aksesibilitas
            $table->integer('likes')->default(0); // Jumlah likes
            $table->integer('comments')->default(0); // Jumlah komentar
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key ke user yang mengupload photo
            $table->foreignId('album_id')->constrained()->onDelete('cascade'); // Foreign key ke tabel albums
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('photos');
    }
}

