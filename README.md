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




