reading-plans
## 概要
ログインユーザーの読書計画を一覧表示し、状態（未読、読書中、読了）で絞り込む
ログインユーザーの読書計画を新規登録、更新、削除できる

以下URL
GET /reading-plans //一覧表示
GET /reading-plans?status=xxx　//状態絞り込み
POST /reading-plans/{plan}/complete　//読了する
GET /reading-plans/create　//新規登録
GET /reading-plans/{plan}/edit　//編集
DELETE /reading-plans/{plan}　//削除

## 必要なもの
- readingplanController
→状態絞り込みができる、CRUD操作ができる

- \App\Enums\ReadingPlanStatus::cases()
→Enums（列挙型）に定義されている全てのcaseを配列で取得するメソッド
planning,reading,compleatedを定義

- $readingPlans
→readingplansテーブルを作成し、それぞれのユーザーの読書計画をリレーションで繋げる
id,
user_id(cascade,外部キー),
book_id(cascade,外部キー),
status,
reminded_at,
target_date(期日),
created_at・updated_at(timestmp)

users->readinPlans 1対多
books->readingplans 1対多

## シーディング要件
山田太郎のみ作成
book_id = 1,期日:3日後,状態:読書中(青) →　リマインダー通知来る
book_id = 2,期日:5日後,状態:読了(緑) →　
book_id = 3,期日:3日後,状態:読了(青)　→　
book_id = 4,期日:昨日,状態:期限切れ(赤) →　期限切れ通知来る

## バッチ処理
app/Console/Commands
期限の切れた計画のステータスを期限切れに変更する→未確認

以下の状態の計画にリマインダー通知を発火する
- 期限切れ
- 期日まで3日


## ReadingPlanRequest
新規登録時
書籍:requierd 書籍を選択してください
期日:required 期日を選択してください
　　 target_date.after_or_equal　本日以降の日付を選択してください

更新時
書籍:requierd 書籍を選択してください
期日:required 期日を選択してください
→本日以前の期日を選択した場合、ステータスは期限切れになる

## 通知処理
通知処理をQueue Job化し、処理失敗時にはLaravelのfailed_jobsテーブルへ記録される。
これを実装しているため、「sail artisan queue:work」このコマンドを必ず実行する必要がある。
