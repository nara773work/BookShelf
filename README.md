## 基本情報
プロジェクト名：BookShelf 書籍管理アプリ

## 概要
「BookShelf　書籍管理アプリ」は、書籍・レビュー・ジャンル・読書計画・レポートなどを管理できるWebアプリケーションである。
Laravelを用いてCRUD機能、認証・認可、公開API、リマインダー通知などを実装している。

## 基礎機能
・認証(Laravel Fortify)
　未認証時はbooks.index,books.show,rankingのみ閲覧可能。
　そのほかはAuth Middlewareを実装しており、未認証時は/login画面にリダイレクトされる。

・書籍CRUD,レビューCRUD,ジャンルCRUD
　一覧表示(index)、新規登録(create・store)、詳細表示(show)、更新(update)、削除(destroy)を行う。

・お気に入り登録
　BookControllerではお気に入り登録(toggle)ができる。

・いいね登録
　ReviewControllerではいいね登録(toggle)ができる。

・ランキング機能
　ゲストユーザーも閲覧できる。
　平均評価が降順に表示される。(TOP10まで)

・公開API
　GET系は認証不要
　POST / PUT / DELETE はLaravel Sanctumによる認証が必要
　エンドポイント一覧を下記に表記。

## 応用機能　
・検索フィルタ
　著者やタイトルに含まれる文字で検索をかけることができる。
　ジャンルで絞り込みができる。
　並び替えができる。
　それぞれを維持したままページネーションで書籍が取得できる。

・ISBN検索(Google Books使用)
　Google Books APIを利用し、ISBNを入力すると書籍情報（タイトル・著者・概要など）が自動入力される。

・マイ読書レポート
　動作確認用として山田太郎(ID=1)のみシナリオありのダミーデータを投入している。
　認可確認用として鈴木花子(ID=2)にも1つダミーデータを投入している。
　シーディング要件にその内容を示す。

・公開APIへの Sanctum によるトークン認証追加

・読書計画とリマインダー通知
　ステータスを読書中、読了、期限切れ（読了にならずに日付が本日を過ぎてしまった）の3つに設定する。
　期限切れになった計画を自動的に期限切れステータスへ変更。
　期日が残り三日になった計画にリマインダー通知を、期限が過ぎてしまった計画に期限切れ通知をそれぞれ発火する。

※docsにそれぞれの詳細設計をまとめたので各機能の詳しい内容はそちらを参照。

## APIエンドポイント一覧
GET　/api/v1/books　　　　　　 ：書籍一覧を取得する
GET　/api/v1/books/{book}     ：書籍詳細を取得する
POST　/api/v1/books　　　　　　：書籍を登録する
PUT　/api/v1/books/{book}     ：書籍を更新する
DELETE　/api/v1/books/{book}　：書籍を削除する
※Laravel Sanctumを導入し、認証＋認可を行っている。
　Bearerトークン（Authorization ヘッダ）による認証方式である。

認可を通す方法
1.POST：http://localhost/api/v1/login
2.メールアドレス・パスワードを送信
3.レスポンスのtokenを取得
4.Authorizationヘッダーへ設定

※以下のようにヘッダーを設定しておくこと。
Accept: application/json
Content-Type: application/json
Authorization: Bearer {token}

## シーディング要件
ReadingPlanSeeder
・通知が発火するパターン、しないパターンを網羅するために
　1.読書中で、期日まで残り2日（3日以内なのでリマインド通知発火）
　2.読書中で、期日まで残り4日（3日以内ではないのでリマインド通知は発火されない）
　3.読了で、期日まで2日（読了しているのでリマインド通知は発火されない）
　4.期限切れ（期限切れなので期限切れ通知が発火される）
→　動作確認の効率向上のため、ID=1（山田太郎）にシナリオを集約する
   また、認可確認のためID=2(鈴木花子)にも1と同じ条件の計画を投入する

## ER図
![ER図](docs/ER.png)

## 技術スタック
・OS : Windows11
・PHP : 8.5
・Laravel : 10.x
・DB : MySQL 8.4
・フロントエンド : Vite, Tailwind CSS ^3.4.0, @tailwindcss/forms
・開発ツール : Docker, Laravel Sail, phpMyAdmin

## 環境構築手順
1.gitリポジトリをクローンした後、プロジェクトに移動する
//git clone <リポジトリURL>
//cd <プロジェクト名>

2.「.env」を作成する
//cp .env.example .env

3.Composer依存パッケージをインストールする
//composer install

4.sailを起動する
//sail up -d

5.アプリケーションキーを作成する
//./vendor/bin/sail artisan key:generate

6.初期データ投入
//sail artisan migrate:fresh --seed

7.Viteを設定する
//sail npm install
//sail npm run dev
こちらは常に稼働させておくこと

## 環境開発URL
http://localhost/books

## 開発者
奈良那々美








