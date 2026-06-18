<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = ['rating', 'comment', 'user_id', 'book_id'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function books()
    {
        return $this->hasMany(Book::class);
    }
    public function reviewLikes()
    {
        return $this->belongsToMany(User::class, 'review_likes', 'review_id','user_id');
    }
}
