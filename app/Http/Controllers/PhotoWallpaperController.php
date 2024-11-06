<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Album;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PhotoWallpaperController extends Controller
{
    public function index()
    {
        // Fetch authenticated user, if any
        $user = auth()->user();
    
        // Fetching all photos with user relations and comments
        $photos = Photo::with(['user', 'comments.user'])->get()->map(function ($photo) {
            // Add isLiked attribute
            $photo->isLiked = $photo->likes()->where('user_id', auth()->id())->exists();
    
            // Ensure comments is an array (if comments is null)
            if (!isset($photo->comments)) {
                $photo->comments = [];
            }
    
            return $photo;
        });
    
        // Prepare user data, handling the case when user is null
        $userData = $user ? [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'profile_picture' => $user->profile_picture ?? asset('storage/profile_pictures/default.jpg'), // Default picture
        ] : null;
    
        // Sending data to the frontend using Inertia
        return Inertia::render('Dashboard', [
            'photos' => $photos,
            'user' => $userData,
            
        ]);
    }
    
    public function create()
    {
        // Fetch albums to populate the dropdown for photos
        $albums = Album::all();

        return Inertia::render('Photos/AddPhotos', [
            'albums' => $albums,
        ]);
    }
    public function destroy($id)
    {
        $photo = Photo::find($id);
        
        if (!$photo) {
            return response()->json(['message' => 'Photo not found'], 404);
        }
    
        // Pastikan pengguna memiliki izin untuk menghapus foto ini
        // Jika menggunakan policy atau middleware untuk otorisasi
    
        $photo->delete();
    
        return response()->json(['message' => 'Photo deleted successfully'], 200);
    }
    
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|image|max:2048', // Validasi file upload
        ]);
    
        // Tangani upload file
        $filePath = $request->file('file')->store('photos', 'public');
    
        // Buat entri foto baru
        $photo = Photo::create([
            'title' => $request->title,
            'description' => $request->description,
            'url' => '/storage/' . $filePath,
            'user_id' => auth()->id(),
        ]);
    
        // Tambahkan log untuk memastikan foto disimpan
        \Log::info('New photo added:', $photo->toArray());
    
        // Kembalikan data foto untuk frontend
        return response()->json([
            'id' => $photo->id,
            'url' => $photo->url,
            'alt' => '', // Opsional
        ]);
    }
    
    public function likePhoto(Request $request, Photo $photo)
    {
        return $this->toggleLike($photo);
    }

    private function toggleLike($model)
    {
        $userId = auth()->id();
        $like = $model->likes()->where('user_id', $userId)->first();

        if ($like) {
            // If already liked, unlike
            $like->delete();
            $model->decrement('likes');
            $action = 'unliked';
        } else {
            // If not liked, like
            $model->likes()->create(['user_id' => $userId]);
            $model->increment('likes');
            $action = 'liked';
        }

        return response()->json([
            'action' => $action,
            'likes' => $model->likes,
        ]);
    }

    public function showPhoto($id)
    {
        // Fetch photo with comments and user
        $photo = Photo::with(['comments.user'])->findOrFail($id);

        // Ensure comments is an array
    

        return Inertia::render('Photos/PhotoShow', ['photo' => $photo]);
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            // Tambahkan validasi lain yang diperlukan
        ]);

        // Temukan foto berdasarkan ID
        $photo = Photo::findOrFail($id);

        // Update foto dengan data baru
        $photo->title = $validatedData['title'];
        $photo->description = $validatedData['description'];
        // Simpan perubahan
        $photo->save();

        return response()->json(['message' => 'Photo updated successfully!', 'photo' => $photo]);
    }

}
