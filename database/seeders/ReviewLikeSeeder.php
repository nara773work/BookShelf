<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewLikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviews = Review::all();
        $users = User::all();

        foreach ($reviews as $review) {   // 自分以外のユーザーIDを取得
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
