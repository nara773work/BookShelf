reading-Plans
//概要
ログインユーザーの読書計画を一覧表示し、状態（未読、読書中、読了）で絞り込む
ログインユーザーの読書計画を新規登録、更新、削除できる

以下URL
GET /reading-plans //一覧表示
GET /reading-plans?status=xxx　//状態絞り込み
POST /reading-plans/{plan}/complete　//読了する
GET /reading-plans/create　//新規登録
GET /reading-plans/{plan}/edit　//編集
DELETE /reading-plans/{plan}　//削除

//必要変数
index
\App\Enums\ReadingPlanStatus::cases()
$readingPlans

必要なもの
・readingplanController
→状態絞り込みができる、CRUD操作ができる
・\App\Enums\ReadingPlanStatus::cases()
→Enums（列挙型）に定義されている全てのcaseを配列で取得するメソッド
planning,reading,compleatedを定義
・$readingPlans
→readingplansテーブルを作成し、それぞれのユーザーの読書計画をリレーションで繋げる
id,user_id(cascade,外部キー),book_id(cascade,外部キー),
target_date(期日),created_at・updated_at(timestmp)
users->readinPlans 1対多
books->readingplans 1対多