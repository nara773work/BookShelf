BookControllerTest
GenreControllerTest
ReviewControllerTest
//概要
各Controllerに記載されている機能をテストする

test_コントローラー名_index（Book,Genreのみ）(Genreは認証必須)
・GETメソッドで一覧ページを表示する　
・表示しているビューが「コントローラー名.index」である

test_コントローラー名_show（Book,Genreのみ）(Genreは認証必須)
・GETメソッドで詳細ページを表示する　
・表示しているビューが「コントローラー名.show」である

test_コントローラー名_create（Book,Genreのみ）（認証必須）
・GETメソッドで作成ページを表示する　
・表示しているビューが「コントローラー名.create」である

test_コントローラー名_store（認証必須）
・POSTメソッドでデータを送信する　
・データがDBに保存されているか検証する
・book.index(書籍の登録),book.show(レビューの登録),genre.index(ジャンルの登録)にリダイレクトされる
・DBの中間テーブルbook_genreにも保存されている

test_コントローラー名_edit（認証必須）
・GETメソッドで編集ページを表示する
・表示しているビューが「コントローラー名.edit」である

test_コントローラー名_update（認証必須）
・PUTメソッドでデータが更新される
・DBが上書きされているか検証する
・book.index(書籍の登録),book.show(レビューの登録),genre.index(ジャンルの登録)にリダイレクトされる

test_コントローラー名_delete（認証必須）
・DELETEメソッドでデータが削除されるか
・DBからも削除されているか
・book.index(書籍の削除),book.show(レビューの削除),genre.index(ジャンルの削除)にリダイレクトされる
※書籍が紐づいてるジャンルは削除できない

test_toggle（認証必須）
・POSTメソッドでお気に入りといいね登録する
・DBのfavoritebooksテーブル（お気に入り）,review_Likes（いいね）テーブルにも紐づける
・2回押すとDBのそれぞれのテーブルから削除される

FavoriteControllerTest
RankingControllerTest
//概要
各画面が表示されているかテストする

test_Controller名（認証必須）
・GETメソッドで「ranking」,「favorites」ページを表示する
・表示しているビューがranking.index(ランキング),favorites.index(お気に入り)である

ApiBookControllerTest
//概要
APIのコントローラーの機能をテストする

test_コントローラー名_index（Book,Genreのみ）(Genreは認証必須)
・GETメソッドで一覧ページを表示する　
・表示しているビューが「コントローラー名.index」である

test_コントローラー名_show（Book,Genreのみ）(Genreは認証必須)
・GETメソッドで詳細ページを表示する　
・表示しているビューが「コントローラー名.show」である

test_コントローラー名_create（Book,Genreのみ）（認証必須）
・GETメソッドで作成ページを表示する　
・表示しているビューが「コントローラー名.create」である

test_コントローラー名_store（認証必須）
・POSTメソッドでデータを送信する　
・データがDBに保存されているか検証する
・book.index(書籍の登録),book.show(レビューの登録),genre.index(ジャンルの登録)にリダイレクトされる
・DBの中間テーブルbook_genreにも保存されている

test_コントローラー名_edit（認証必須）
・GETメソッドで編集ページを表示する
・表示しているビューが「コントローラー名.edit」である

test_コントローラー名_update（認証必須）
・PUTメソッドでデータが更新される
・DBが上書きされているか検証する
・book.index(書籍の登録),book.show(レビューの登録),genre.index(ジャンルの登録)にリダイレクトされる

test_コントローラー名_delete（認証必須）
・DELETEメソッドでデータが削除されるか
・DBからも削除されているか
・book.index(書籍の削除),book.show(レビューの削除),genre.index(ジャンルの削除)にリダイレクトされる
※書籍が紐づいてるジャンルは削除できない