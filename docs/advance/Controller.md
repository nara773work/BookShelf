ReadingPlanControllerTest
//概要
コントローラーの機能をテストする

test_コントローラー名_index (認証必須)
・GETメソッドで一覧ページを表示する　
・表示しているビューが「コントローラー名.index」である

test_コントローラー名_show(認証必須)
・GETメソッドで詳細ページを表示する　
・表示しているビューが「コントローラー名.show」である

test_コントローラー名_create（認証必須）
・GETメソッドで作成ページを表示する　
・表示しているビューが「コントローラー名.create」である

test_コントローラー名_store（認証必須）
・POSTメソッドでデータを送信する　
・データがDBに保存されているか検証する
・reading-plans.index(読書計画の登録)にリダイレクトされる

test_コントローラー名_edit（認証必須）
・GETメソッドで編集ページを表示する
・表示しているビューが「コントローラー名.edit」である

test_コントローラー名_update（認証必須）
・PUTメソッドでデータが更新される
・DBが上書きされているか検証する
・reading-plans.index(読書計画の登録)にリダイレクトされる

test_コントローラー名_delete（認証必須）
・DELETEメソッドでデータが削除されるか
・DBからも削除されているか
・reading-plans.index(読書計画の削除)にリダイレクトされる

reportContoller
//画面が表示されているかテストする

test_index
・GETメソッドでマイレポートを表示する
・表示しているビューがreport.indexである
未
・総レビュー数、読了数、平均評価の値が正確である
・評価分布が正確である
・高評価TOP5が正確である
・ジャンル別評価傾向TOP5が正確である

NotificationsController
//通知が来る