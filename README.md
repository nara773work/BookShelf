基本情報
プロジェクト名：BookShelf

概要
実装機能一覧

基礎機能
・認証(Fortifyを使用)
　未認証時はbooks.index,books.show,rankingのみ閲覧可能。
　そのほかはmiddlewearを実装しており、未認証時は/login画面にリダイレクトされる。

・書籍CRUD,レビューCRUD,ジャンルCRUD
　一覧画面表示(index)、新規登録(create,store)、詳細画面表示(show)、更新(update)、削除(delete)を行う。
  BookControllerではisbn検索(isbn)ができる。

・お気に入り登録
　BookControllerではお気に入り登録(toggle)ができる。

・いいね登録
　ReviewControllerではいいね登録(toggle)ができる。

・ランキング機能
　ゲストユーザーも閲覧できる。
　平均評価が降順に表示される。(TOP10まで)

・公開API（認証なしCRUD）
　エンドポイント一覧を下記に表記。

応用機能　
・検索フィルタ
　著者やタイトルに含まれる文字で検索をかけることができる。
　ジャンルで絞り込みができる。
　並び替えができる。
　それぞれを維持したままページネーションで書籍が取得できる。

・ISBN検索(Google Books使用)
　ISBNを入力すると書籍登録画面が自動補完される。

・マイ読書レポート
　テストユーザーとして山田太郎(ID=1)のみダミーデータを作成。以下にその内容を示す。

・公開APIへの Sanctum によるトークン認証追加

・読書計画書とリマインダー通知
　ステータスが読書中、読了、期限切れ（読了にならずに日付が本日を過ぎてしまった）の3つである。
　期限切れになった計画を自動的に期限切れステータスへ変更。
　期日が残り三日になった計画にリマインダー通知を、期限が過ぎてしまった計画に期限切れ通知をそれぞれ発火する。

※Docsにそれぞれの詳細設計をまとめたので各機能の詳しい内容はそちらを参照。

APIエンドポイント一覧
GET　/api/v1/books　　　　　　 ：書籍一覧を取得する
GET　/api/v1/books/{book}     ：書籍詳細を取得する
POST　/api/v1/books　　　　　　：書籍を登録する
PUT　/api/v1/books/{book}     ：書籍を更新する
DELETE　/api/v1/books/{book}　：書籍を削除する
※Laravel Sanctumを導入し、認証＋認可を行っている。
　Bearerトークン（Authorization ヘッダ）による認証方式である。

認可を通す方法
POST：http://localhost/api/v1/loginにアクセスする（bodyにメールアドレスとパスワードを入力してPOSTする）
tokenが返ってくるので、それをヘッダーのAuthorizationの値としてBearer 1|xxxxxxxxxxxxxxxxの×部分に入力する。
※キーにAccept、その値にapplication/json、キーにContent-Type、その値にContent-Typeに設定しておく。

ER図
![ER図](docs/ER.png)

技術スタック
・OS : Windows11
・PHP : 8.5
・Laravel : 10.x
・DB : MySQL 8.4
・フロントエンド : Vite, Tailwind CSS ^3.4.0, @tailwindcss/forms
・開発ツール : Docker, Laravel Sail, phpMyAdmin

開発者
奈良那々美








