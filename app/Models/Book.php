<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;
    protected $fillable = 
    [
        'name', 'author', 'isbn_code', 'publication_date','description','img','user_id'
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'book_genre', 'book_id','genre_id');
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
