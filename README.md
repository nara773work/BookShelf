基本情報
//プロジェクト名：BookShelf

//概要

//ER図
![ER図](docs/ER.png)

//技術スタック
・OS : Windows11
・PHP : 8.5
・Laravel : 10.x
・DB : MySQL 8.4
・フロントエンド : Vite, Tailwind CSS ^3.4.0, @tailwindcss/forms
・開発ツール : Docker, Laravel Sail, phpMyAdmin

//開発者
　奈良那々美

//APIエンドポイント一覧
GET　/api/v1/books　　　　　　 ：書籍一覧を取得する
GET　/api/v1/books/{book}     ：書籍詳細を取得する
POST　/api/v1/books　　　　　　：書籍を登録する
PUT　/api/v1/books/{book}     ：書籍を更新する
DELETE　/api/v1/books/{book}　：書籍を削除する

//実装機能
・認証
・書籍CRUD,レビューCRUD,ジャンルCRUD
・お気に入り登録
・いいね
・ランキング機能
・公開API（認証なしCRUD）

//応用機能　未
・検索フィルタ
・ISBN検索
・マイ読書レポート
・公開APIへの Sanctum によるトークン認証追加
・読書計画書とリマインダー通知


//認証、認可
　公開
　　・書籍一覧の閲覧　
　　・書籍詳細の閲覧
　　・ランキングの閲覧

　認証済みユーザー（middlewear使用）
　　・書籍の新規登録
　　・レビューの新規登録
　　・ジャンル一覧の閲覧
　　・ジャンル詳細の閲覧
　　・ジャンルの編集　//作成者のみ編集可？？
　　・お気に入り登録
　　・いいね登録
　　・マイ読書レポートの閲覧
　　・読書計画一覧の閲覧

　作成者のみ　（BookPolicy,ReviewPolicy作成）
　　・書籍の編集（編集画面閲覧、更新、削除）
　　・レビュー編集（編集画面閲覧、更新、削除）


//バリデーション
　ユーザー新規登録
    name：必須|max:50|文字列
    必須：名前を入力してください
　  max：50字以内で入力してください
　  文字列：文字列で入力してください

    email：必須|max:100|メール形式|一意
    必須：メールアドレスを入力してください
    max：100字以内で入力してください
    email：メール形式で入力してください
    unique:このメールアドレスは既に存在しています

    password：必須|min:8|max:20
    必須：パスワードを入力してください
　  min：8字以上20字以内で入力してください
　  max：8字以上20字以内で入力してください

　ログイン
    email：必須
    必須：メールアドレスを入力してください

　  password：必須
　  必須：パスワードを入力してください

    どちらか間違っている場合：メールアドレス、またはパスワードが正しくありません

　書籍登録、編集（公開APIも同様）
    title：必須|max:150
    必須：本のタイトルを入力してください
　  max：150字以内で入力してください

    authore：必須|max:100|文字列
    必須：著者を入力してください
　  max：100字以内で入力してください
    文字列：文字列で入力してください

    digits：必須|digits13|unique
    必須：コードを入力してください
    digits：13字で入力してください
    unique：そのisbnコードは既に存在しています

    published_date：必須
    必須：出版日を入力してください

    description：任意|max:255
    max：255字以内で入力してください

    image_url：任意

    genre：必須
　　必須：ジャンルを選択してください

　レビュー登録、編集（公開APIも同様）
　　rating：必須
　　必須：評価を選択してください

　　comment：任意|max:255|文字列
    max：255字以内で入力してください
　  文字列：文字列で入力してください

　ジャンル登録、編集（公開APIも同様）
　　name：必須|max:50
　　必須：ジャンル名を入力してください
　　max：50字以内で入力してください

//リダイレクト先
    正常系
        会員登録：books.index
        書籍登録：books.index
        書籍更新：books.index
        書籍削除：books.index
        レビュー登録：books.show
        レビュー更新：books.show
        レビュー削除：books.show
        ジャンル登録：genre.index
        ジャンル更新：genre.index
        ジャンル削除：genre.index
        toggle(お気に入り登録・削除)：back(books.show)

    異常系
    　　　ジャンル削除→genre.index

    バリデーションエラー時
    　　　エラーメッセージを表示し元のフォームに戻す

//フラッシュメッセージ
    scusses
        web
        書籍登録：書籍を登録しました
        書籍更新：書籍を更新しました
        書籍削除：書籍を削除しました
        レビュー登録：レビューを登録しました
        レビュー更新：レビューを更新しました
        レビュー削除：レビューを削除しました
        ジャンル登録：ジャンルを登録しました
        ジャンル更新：ジャンルを更新しました
        ジャンル削除：ジャンルを削除しました
        
        API　ステータスコード
        書籍一覧取得：書籍一覧の取得に成功しました　200
        書籍詳細取得：書籍の詳細取得に成功しました　200
        書籍登録：書籍の登録に成功しました　201
        書籍更新：書籍の更新に成功しました　200
        書籍削除：書籍の削除に成功しました　204

    error
        ジャンル削除：紐づいている書籍があるため削除できません

        API　ステータスコード
        詳細、更新、削除(存在しないID指定)：書籍が存在しません　404


//データ要件・テーブル仕様書
　テーブル仕様書を見せる（ここはあとで消す）


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

//テスト要件