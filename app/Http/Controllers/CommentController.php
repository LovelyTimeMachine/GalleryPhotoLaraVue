<?php

namespace App\Http\Controllers;
use App\Models\Photo;
use App\Models\Album;
use App\Models\Like;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
// app/Http/Controllers/CommentController.php
public function store(Request $request, $photoId)
{
    // Ensure user is authenticated
    if (!auth()->check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Validate the request
    $request->validate([
        'content' => 'required|string|max:255',
    ]);

    // Ensure the photo exists
    $photo = Photo::find($photoId);
    if (!$photo) {
        return response()->json(['error' => 'Photo not found.'], 404);
    }

    // Create the comment
    try {
        $comment = Comment::create([
            'photo_id' => $photoId,
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        // Load the user relationship
        $comment->load('user');

        // Return the newly created comment along with user info
        return response()->json(['comment' => $comment], 201);
    } catch (\Exception $e) {
        \Log::error('Comment creation failed: ' . $e->getMessage());
        return response()->json(['error' => 'Could not create comment.'], 500);
    }
}


}
