<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = 
    [
        'title',
        'author',
        'isbn',
        'published_date',
        'description',
        'image_url',
        'user_id',
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }
    public function favoritebooks()
    {
        return $this->belongsToMany(User::class,'favorites','book_id','user_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function user()
    {
        return $this->belongsTo(user::class);
    }
}
