<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookGenre extends Model
{
    use HasFactory;
    public function genres()
    {
        return $this->hasMany(Genre::class);
    }
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
