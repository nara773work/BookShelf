<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Genre::create([                  
            'name' => '小説',
            'name' => 'ビジネス',
            'name' => '技術書',
            'name' => '自己啓発',
            'name' => 'エッセイ',
            'name' => '歴史',
            'name' => '科学',
            'name' => '芸術',
            'name' => '料理',
            'name' => '旅行',
        ]);
    }
}
