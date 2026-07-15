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
        $count_stop = 10;

        //1冊あたり最低2件のレビューを作成するために、IDに2を割り当てる。
        $reviewCounts = [];

        foreach ($books as $book) {
            $reviewCounts[$book->id] = 2;
        }
            
        //32件まで増やすために、ランダムにIDを抽出し、IDの持つ数が4以下で、かつ合計が32に収まるように割り振る
        while($count_start < $count_stop )  {

            $book = $books->random();

            if ($reviewCounts[$book->id] < 4) {

                $reviewCounts[$book->id]++;
                $count_start++;

            }
            }  

        foreach ($books as $book) {
            //先ほど割り当てた数を数字とみなし、レビュー数とする。
            $reviewCount = $reviewCounts[$book->id];

            $selectedUsers = collect($users)
            ->shuffle()
            ->take($reviewCount);

            foreach ($selectedUsers as $userId) {

            Review::create([
                'user_id' => $userId,
                'book_id' => $book->id,
                'rating' => rand(1, 5),
                'comment' => fake()->realText(100),
            ]);
    }
}             
        }
    }

    
