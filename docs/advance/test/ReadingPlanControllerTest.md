ReadingPlanControllerTest
## 概要
コントローラーの機能をテストする

## test_ReadingPlan_index (認証必須)
- GETメソッドで一覧ページを表示する　
- 表示しているビューが「コントローラー名.index」である
- 基本サマリー: 総レビュー数、読了冊数（ユニーク書籍数）、平均評価点
- 評価分布: 1〜5星ごとの件数を横バーで表示
- 高評価書籍TOP5:4星以上の書籍を評価の高い順に最大5件表示（書籍詳細リンク付き）
- ジャンル別評価傾向TOP5:ジャンルごとの平均評価と件数を高い順に最大5件表示

## test_ReadingPlan_show(認証必須)
- GETメソッドで詳細ページを表示する　
- 表示しているビューが「コントローラー名.show」である

## test_ReadingPlan_create（認証必須）
- GETメソッドで作成ページを表示する　
- 表示しているビューが「コントローラー名.create」である

## test_ReadingPlan_store（認証必須）
- POSTメソッドでデータを送信する　
- データがDBに保存されているか検証する
- reading-plans.index(読書計画の登録)にリダイレクトされる

## test_ReadingPlan_edit（認証必須）
- GETメソッドで編集ページを表示する
- 表示しているビューが「コントローラー名.edit」である

## test_ReadingPlan_update（認証必須）
- PUTメソッドでデータが更新される
- DBが上書きされているか検証する
- reading-plans.index(読書計画の登録)にリダイレクトされる

## test_ReadingPlan_delete（認証必須）
- DELETEメソッドでデータが削除されるか
- DBからも削除されているか
- reading-plans.index(読書計画の削除)にリダイレクトされる



