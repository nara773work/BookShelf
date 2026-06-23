<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use App\Models\Favorite;

class FavoriteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $books = Book::pluck('id')->toArray();

        foreach($users as $user)
        {   
            //$random_bookidに3～5冊（ランダムで数が決まる）のbook_idが配列で渡される。
            $random_book = array_rand($books, rand(3, 5)); 

            $random_bookid = array_map(fn($key) => $books[$key], $random_book);

            $user->favoritebooks()->syncWithoutDetaching($random_bookid);         
        }        
    }
}
