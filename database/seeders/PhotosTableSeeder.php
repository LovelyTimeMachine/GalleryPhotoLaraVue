<?php

namespace Database\Seeders;

use App\Models\Photo;
use Illuminate\Database\Seeder;

class PhotosTableSeeder extends Seeder
{
    public function run()
    {
        // Mengisi tabel photos dengan data contoh
        Photo::create([
            'title' => 'Sunset View',
            'description' => 'A beautiful sunset over the mountains.',
            'tags' => 'sunset, mountains, nature',
            'url' => 'https://via.placeholder.com/300',
            'alt' => 'Sunset over mountains',
            'likes' => 120,
            'comments' => 45,
            'user_id' => 1, // Pastikan user_id sesuai dengan pengguna yang ada di tabel users
            'album_id' => 1, // Pastikan album_id sesuai dengan album yang ada di tabel albums
        ]);

        Photo::create([
            'title' => 'Forest Trail',
            'description' => 'A serene forest trail during autumn.',
            'tags' => 'forest, autumn, nature',
            'url' => 'https://via.placeholder.com/300',
            'alt' => 'Forest trail',
            'likes' => 98,
            'comments' => 23,
            'user_id' => 2,
            'album_id' => 1,
        ]);

        Photo::create([
            'title' => 'City Skyline',
            'description' => 'A panoramic view of the city skyline at night.',
            'tags' => 'city, skyline, night',
            'url' => 'https://via.placeholder.com/300',
            'alt' => 'City skyline at night',
            'likes' => 150,
            'comments' => 60,
            'user_id' => 3,
            'album_id' => 2,
        ]);

        Photo::create([
            'title' => 'Ocean Waves',
            'description' => 'Waves crashing against the shore.',
            'tags' => 'ocean, waves, beach',
            'url' => 'https://via.placeholder.com/300',
            'alt' => 'Ocean waves',
            'likes' => 200,
            'comments' => 80,
            'user_id' => 4,
            'album_id' => 2,
        ]);

        Photo::create([
            'title' => 'Desert Dunes',
            'description' => 'Sand dunes in the desert during sunset.',
            'tags' => 'desert, dunes, sunset',
            'url' => 'https://via.placeholder.com/300',
            'alt' => 'Desert dunes at sunset',
            'likes' => 75,
            'comments' => 10,
            'user_id' => 5,
            'album_id' => 3,
        ]);

        Photo::create([
            'title' => 'Mountain Peak',
            'description' => 'A breathtaking view from a mountain peak.',
            'tags' => 'mountain, peak, nature',
            'url' => 'https://via.placeholder.com/300',
            'alt' => 'Mountain peak',
            'likes' => 65,
            'comments' => 12,
            'user_id' => 6,
            'album_id' => 3,
        ]);
    }
}
