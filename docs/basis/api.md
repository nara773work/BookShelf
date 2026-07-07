//API resourceの構造
    //共通
    'id' => 書籍id
    'title' => 書籍タイトル
    'author'=>　著者
    'isbn'=>　isbnコード
    'published_date' => 出版日
    'description' => 説明（ない場合はnull）
    'image_url' => 画像（ない場合はnull）
    'genres'=>[ジャンル名]
            
    //indexでのみ取得
    'reviews_avg_rating'=>平均評価
    'reviews_count'=>レビュー件数
            
    //showでのみ取得
    'reviews' : [
        {
            'user_name' => レビュー作成者の名前
            'rating' => 評価
            'comment' => コメント
            'created_at' => 'Y-m-d H:i:s'
            'updated_at' => 'Y-m-d H:i:s',
        },
    ];

    'created_at' => 'Y-m-d H:i:s'
    'updated_at' =>　'Y-m-d H:i:s',