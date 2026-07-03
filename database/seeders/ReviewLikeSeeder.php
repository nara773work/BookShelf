<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Review;
use App\Models\ReviewLike;

class ReviewLikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviews = Review::all();
        $users = User::all();

        foreach($reviews as $review)
        {   //自分以外のユーザーIDを取得
            $userId = $users->where('id', '!=', $review->user_id)->pluck('id')->toArray();     

            $likeCount = rand(0, 3);

            if ($likeCount === 0) {
                continue;
            }

            $randomUserIds = collect($userId)
            ->shuffle()
            ->take($likeCount)
            ->toArray();

            $review->likedByUsers()
            ->syncWithoutDetaching($randomUserIds);
        }   
    }
}
