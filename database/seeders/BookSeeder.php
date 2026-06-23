<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\User; 


class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $book1 = Book::firstOrCreate([                  
            'title' => '吾輩は猫である',
            'author' => '夏目漱石',
            'isbn' => '9784101010014',
            'published_date' => '1905-01-01',
            'image_url' => 'img',
            'user_id' => User::first()->id,
        ]);
        $book1->genres()->sync([1]);

        $book2=Book::firstOrCreate([                  
            'title' => '人を動かす',
            'author' => 'D・カーネギー',
            'isbn' => '9784422100524',
            'published_date' => '1936-10-01',
            'image_url' => 'img',
            'user_id' => User::first()->id
        ]);
        $book2->genres()->sync([2,4]);

        $book3=Book::firstOrCreate([                  
            'title' => ' リーダブルコード',
            'author' => ' Dustin Boswell',
            'isbn' => '9784873115658',
            'published_date' => '2012-06-23',
            'image_url' => 'img',
            'user_id' => User::first()->id
        ]);
        $book3->genres()->sync([3]);

        $book4=Book::firstOrCreate([                  
            'title' => '7つの習慣',
            'author' => 'スティーブン・R・コヴィー',
            'isbn' => '9784863940246',
            'published_date' => '2013-08-30',
            'image_url' => 'img',
            'user_id' => User::first()->id
        ]);
        $book4->genres()->sync([2,4]);

        $book5=Book::firstOrCreate([                  
            'title' => '坊っちゃん',
            'author' => '夏目漱石',
            'isbn' => '9784101010021',
            'published_date' => '1906-04-01',
            'image_url' => 'img',
            'user_id' => User::first()->id
        ]);
        $book5->genres()->sync([1]);

        $book6=Book::firstOrCreate([                  
            'title' => 'サピエンス全史',
            'author' => 'ユヴァル・ノア・ハラリ',
            'isbn' => '9784309226712',
            'published_date' => '2016-09-08',
            'image_url' => 'img',
            'user_id' => User::first()->id
        ]);
        $book6->genres()->sync([6,7]);

        $book7=Book::firstOrCreate([                  
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'isbn' => '9784048930598',
            'published_date' => '2017-12-18',
            'image_url' => 'img',
            'user_id' => User::first()->id
        ]);
        $book7->genres()->sync([3]);

        $book8=Book::firstOrCreate([                  
            'title' => '嫌われる勇気',
            'author' => '岸見一郎・古賀史健',
            'isbn' => '9784478025819',
            'published_date' => '2013-12-13',
            'image_url' => 'img',
            'user_id' => User::first()->id
        ]);
        $book8->genres()->sync([4]);

        $book9=Book::firstOrCreate([                  
            'title' => '火花',
            'author' => '又吉直樹',
            'isbn' => '9784163902302',
            'published_date' => '2015-03-11',
            'image_url' => 'img',
            'user_id' => User::first()->id
        ]);
        $book9->genres()->sync([1]);

        $book10=Book::firstOrCreate([                  
            'title' => 'FACTFULNESS',
            'author' => 'ハンス・ロスリング',
            'isbn' => '9784822289607',
            'published_date' => '2019-01-11',
            'image_url' => 'img',
            'user_id' => User::first()->id
        ]);
        $book10->genres()->sync([2,7]);

        $book11=Book::firstOrCreate([                  
            'title' => 'コンテナ物語',
            'author' => ' マルク・レビンソン',
            'isbn' => '9784822251468',
            'published_date' => '2007-01-18',
            'image_url' => 'img',
            'user_id' => User::first()->id
        ]);
        $book11->genres()->sync([2,6]);
    }
}
