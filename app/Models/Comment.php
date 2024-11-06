<?php

// app/Models/Comment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo_id',
        'user_id',
        'content',
    ];
    

    // Define the relationship with Photo
    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }

    // Define the relationship with User (if applicable)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
