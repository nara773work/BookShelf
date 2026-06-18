<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Book;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::pluck('id')->toArray();
        $books = Book::all();

        $count_start = 0;
        $count_stop = 32;

        while($count_start < $count_stop) {
            foreach ($books as $book) {
                $count = rand(2, 4);
                $random_users = $users[array_rand($users)];

                    Review::create([
                        'user_id' => $random_users,
                        'book_id' => $book->id,
                        'rating'  => rand(3, 5),
                        'comment' => fake()->realText(100),
                    ]);

                $count_start++;

                if ($count_start >= $count_stop) {
                     break;
                }
        }
    }
}
    }
