APIroute

ミドルウェアによって認証・認可を実装する。
→ 更新・削除は作成者のみが実行できる。

----------------------------------------------------------------------
use App\Http\Controllers\Api\v1\BookController;
use App\Http\Controllers\Api\v1\AuthController;

//APICRUD操作
Route::prefix('v1')->group(function () {
    Route::get('/books', [BookController::class,'index'])->name('book.index')->name('books.index');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

    Route::middleware('auth:sanctum')->group(function () {
    Route::post('/books', [BookController::class, 'store']);
    Route::put('/books/{book}',[BookController::class, 'update']);
    Route::delete('/books/{book}', [BookController::class, 'destroy']);});});

//ログイン、ログアウト    
Route::prefix('v1')->group(function () {
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});
-------------------------------------------------------------------------

## APIエンドポイント一覧
- GET　/api/v1/books　　　　　　 ：書籍一覧を取得する　200
- GET　/api/v1/books/{book}     ：書籍詳細を取得する　200
- POST　/api/v1/books　　　　　　：書籍を登録する　　　201
- PUT　/api/v1/books/{book}     ：書籍を更新する　　　200
- DELETE　/api/v1/books/{book}　：書籍を削除する　　　204
存在しないIDの場合、ステータスコードは404となる。
権限不足の場合はステータスコードは403となる。

## API resourceの構造
    共通
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