<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Book;
use App\Models\Favorite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Favorite>
 */
class FavoriteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Favorite::class;

    public function definition(): array
    {
        return [
            'book_id' => Book::factory(),
        ];
    }

     
}
