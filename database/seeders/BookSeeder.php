<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        $book1 = Book::firstOrCreate([
            'title' => '吾輩は猫である',
            'author' => '夏目漱石',
            'isbn' => '9784101010014',
            'published_date' => '1905-01-01',
            'image_url' => 'https://placehold.jp/3d4070/ffffff/300x300.png?text=A',
            'description' => '',
            'user_id' => $users->random()->id,
            'created_at' =>  Carbon::today()->subDays(10),

        ]);
        $book1->genres()->sync([1]);

        $book2 = Book::firstOrCreate([
            'title' => '人を動かす',
            'author' => 'D・カーネギー',
            'isbn' => '9784422100524',
            'published_date' => '1936-10-01',
            'image_url' => 'https://placehold.jp/3d4070/ffffff/300x300.png?text=B',
            'description' => '『人を動かす』は、相手を尊重し、信頼関係を築きながら人間関係を円滑にする方法を説いた名著です。批判や命令ではなく、相手の立場を理解し、誠実な関心や感謝を示すことの大切さを具体例とともに学べます。仕事や家庭など、あらゆる場面で役立つ実践的なコミュニケーション術が詰まった一冊です。',
            'user_id' => $users->random()->id,
            'created_at' =>  Carbon::today()->subDays(12),
        ]);
        $book2->genres()->sync([2, 4]);

        $book3 = Book::firstOrCreate([
            'title' => 'リーダブルコード',
            'author' => 'Dustin Boswell',
            'isbn' => '9784873115658',
            'published_date' => '2012-06-23',
            'image_url' => 'https://placehold.jp/3d4070/ffffff/300x300.png?text=C',
            'description' => '読みやすく保守しやすいコードの書き方を学ぶ本。',
            'user_id' => $users->random()->id,
            'created_at' =>  Carbon::today()->subDays(2),
        ]);
        $book3->genres()->sync([3]);

        $book4 = Book::firstOrCreate([
            'title' => '7つの習慣',
            'author' => 'スティーブン・R・コヴィー',
            'isbn' => '9784863940246',
            'published_date' => '2013-08-30',
            'image_url' => 'https://placehold.jp/3d4070/ffffff/300x300.png?text=D',
            'description' => '『7つの習慣』は、人生や仕事で本質的な成果を上げるための原則を体系的にまとめた自己啓発書です。成功を一時的なテクニックではなく、人間としての在り方に基づいて捉えている点が特徴です。第1の習慣「主体的である」から始まり、「目的を持って始める」「重要事項を優先する」といった個人の自立を促す習慣を学びます。その後、「Win-Winを考える」「理解してから理解される」「相乗効果を生み出す」といった他者との関係性を深め継続的な自己成長の大切さを説いています。人生の指針として長く読み継がれる名著です今なお人気作です。',
            'user_id' => $users->random()->id,
            'created_at' =>  Carbon::today()->subDays(5),
        ]);
        $book4->genres()->sync([2, 4]);

        $book5 = Book::firstOrCreate([
            'title' => '坊っちゃん',
            'author' => '夏目漱石',
            'isbn' => '9784101010021',
            'published_date' => '1906-04-01',
            'image_url' => 'https://placehold.jp/3d4070/ffffff/300x300.png?text=E',
            'description' => '',
            'user_id' => $users->random()->id,
            'created_at' =>  Carbon::today()->subDays(7),
        ]);
        $book5->genres()->sync([1]);

        $book6 = Book::firstOrCreate([
            'title' => 'サピエンス全史',
            'author' => 'ユヴァル・ノア・ハラリ',
            'isbn' => '9784309226712',
            'published_date' => '2016-09-08',
            'image_url' => 'https://placehold.jp/3d4070/ffffff/300x300.png?text=F',
            'description' => '',
            'user_id' => $users->random()->id,
            'created_at' =>  Carbon::today()->subDays(2),
        ]);
        $book6->genres()->sync([6, 7]);

        $book7 = Book::firstOrCreate([
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'isbn' => '9784048930598',
            'published_date' => '2017-12-18',
            'image_url' => 'https://placehold.jp/3d4070/ffffff/300x300.png?text=G',
            'description' => '『Clean Code』は、読みやすく保守しやすいコードを書くための原則をまとめた書籍です。命名、関数設計、コメントの扱いなどを通して、長く品質を保てるコードの書き方を学べます。実務で役立つ考え方が詰まっています。',
            'user_id' => $users->random()->id,
            'created_at' =>  Carbon::today()->subDays(6),
        ]);
        $book7->genres()->sync([3]);

        $book8 = Book::firstOrCreate([//新しい順ではこれが先頭にくる
            'title' => '嫌われる勇気',
            'author' => '岸見一郎・古賀史健',
            'isbn' => '9784478025819',
            'published_date' => '2013-12-13',
            'image_url' => 'https://placehold.jp/3d4070/ffffff/300x300.png?text=H',
            'description' => '『嫌われる勇気』は、他人の評価に縛られず、自分の人生を主体的に生きるための考え方を対話形式で学ぶ本です。',
            'user_id' => $users->random()->id,
            'created_at' =>  Carbon::today()->subDays(1),
        ]);
        $book8->genres()->sync([4]);

        $book9 = Book::firstOrCreate([//古い順ではこれが先頭にくる
            'title' => '火花',
            'author' => '又吉直樹',
            'isbn' => '9784163902302',
            'published_date' => '2015-03-11',
            'image_url' => 'https://placehold.jp/3d4070/ffffff/300x300.png?text=I',
            'description' => '『火花』は、お笑い芸人を目指す若手芸人・徳永と、才能と破天荒さを持つ先輩芸人・神谷との出会いと交流を描いた物語です。徳永は神谷に強く惹かれ、その生き方や笑いへの哲学に影響を受けながらも、自身の芸人としての現実と葛藤していきます。夢を追うことの厳しさや才能とは何か、表現者として生きる意味を問いかける作品で、人間の孤独や憧れ、挫折が繊細に描かれています。芥川賞受賞作としても知られる一冊です。',
            'user_id' => $users->random()->id,
            'created_at' =>  Carbon::today()->subDays(15),
        ]);
        $book9->genres()->sync([1]);

        $book10 = Book::firstOrCreate([
            'title' => 'FACTFULNESS',
            'author' => 'ハンス・ロスリング',
            'isbn' => '9784822289607',
            'published_date' => '2019-01-11',
            'image_url' => 'https://placehold.jp/3d4070/ffffff/300x300.png?text=J',
            'description' => '『Factfulness』は、世界は悪化しているという思い込みに対し、データに基づいて現実を正しく見る重要性を説いた本です。著者は人間が持つ10の思い込みを示し、それが誤解や不安を生む原因であると説明します。統計や事実を使うことで、世界は思ったよりも良くなっている側面が多いことを示し、冷静で正しい判断力を養うことの大切さを教えてくれます。',
            'user_id' => $users->random()->id,
            'created_at' =>  Carbon::today()->subDays(9),
        ]);
        $book10->genres()->sync([2, 7]);

        $book11 = Book::firstOrCreate([
            'title' => 'コンテナ物語',
            'author' => 'マルク・レビンソン',
            'isbn' => '9784822251468',
            'published_date' => '2007-01-18',
            'image_url' => 'https://placehold.jp/3d4070/ffffff/300x300.png?text=K',
            'description' => '',
            'user_id' => $users->random()->id,
            'created_at' =>  Carbon::today()->subDays(5),
        ]);
        $book11->genres()->sync([2, 6]);
    }
}
