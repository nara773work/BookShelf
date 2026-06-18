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
        Book::firstOrCreate([                  
            'name' => '吾輩は猫である',
            'author' => '夏目漱石',
            'isbn_code' => '9784101010014',
            'publication_date' => '1905-01-01',
            'image_url' => 'img',
            'user_id' => User::first()->id
        ]);
        Book::firstOrCreate([                  
            'name' => '人を動かす',
            'author' => 'D・カーネギー',
            'isbn_code' => '9784422100524',
            'publication_date' => '1936-10-01',
            'image_url' => 'img',
            'user_id' => User::first()->id
        ]);
        Book::firstOrCreate([                  
            'name' => ' リーダブルコード',
            'author' => ' Dustin Boswell',
            'isbn_code' => '9784873115658',
            'publication_date' => '2012-06-23',
            'image_url' => 'img',
            'user_id' => User::first()->id
        ]);
        Book::firstOrCreate([                  
            'name' => '7つの習慣',
            'author' => 'スティーブン・R・コヴィー',
            'isbn_code' => '9784863940246',
            'publication_date' => '2013-08-30',
            'image_url' => 'img',
            'user_id' => User::first()->id
        ]);
        Book::firstOrCreate([                  
            'name' => '坊っちゃん',
            'author' => '夏目漱石',
            'isbn_code' => '9784101010021',
            'publication_date' => '1906-04-01',
            'image_url' => 'img',
            'user_id' => User::first()->id
        ]);
        Book::firstOrCreate([                  
            'name' => 'サピエンス全史',
            'author' => 'ユヴァル・ノア・ハラリ',
            'isbn_code' => '9784309226712',
            'publication_date' => '2016-09-08',
            'image_url' => 'img',
            'user_id' => User::first()->id
        ]);
        Book::firstOrCreate([                  
            'name' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'isbn_code' => '9784048930598',
            'publication_date' => '2017-12-18',
            'image_url' => 'img',
            'user_id' => User::first()->id
        ]);
        Book::firstOrCreate([                  
            'name' => '嫌われる勇気',
            'author' => '岸見一郎・古賀史健',
            'isbn_code' => '9784478025819',
            'publication_date' => '2013-12-13',
            'image_url' => 'img',
            'user_id' => User::first()->id
        ]);
        Book::firstOrCreate([                  
            'name' => '火花',
            'author' => '又吉直樹',
            'isbn_code' => '9784163902302',
            'publication_date' => '2015-03-11',
            'image_url' => 'img',
            'user_id' => User::first()->id
        ]);
        Book::firstOrCreate([                  
            'name' => 'FACTFULNESS',
            'author' => 'ハンス・ロスリング',
            'isbn_code' => '9784822289607',
            'publication_date' => '2019-01-11',
            'image_url' => 'img',
            'user_id' => User::first()->id
        ]);
        Book::firstOrCreate([                  
            'name' => 'コンテナ物語',
            'author' => ' マルク・レビンソン',
            'isbn_code' => '9784822251468',
            'publication_date' => '2007-01-18',
            'image_url' => 'img',
            'user_id' => User::first()->id
        ]);
    }
}
