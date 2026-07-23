## 機能テスト

## Controller
1.Auth
2.controller.md
- Api_BookController
- BookController
- ReviewController
- GenreController
(その他)
- RankingController
- FavoriteController

- ページ推移
　ユーザー登録画面、ログイン画面(AuthTest)
  書籍登録・更新・削除画面(Book)
　レビュー登録・更新・削除画面(Review)
　ジャンル登録・更新・削除画面(Genre)
　ランキング画面(ranking)
　お気に入り画面(favorite)
→それぞれに必要な表示がされているか確認

- リレーションテスト
  中間テーブル[Book_genre(user.id,genre.id),favorites(user.id,book.id),reviewLikes(user.id,review.id)]
　書籍登録時にジャンルの中間テーブルに保存、または削除されるか
  お気に入りマークを押したときに、favoritesテーブルに保存、または削除されるか
  いいねマークを押したときに、reviewLikesテーブルに保存、または削除されるか
  書籍削除時にお気に入りも削除されるか
  レビュー削除時にレビューのいいねも削除されるか

  外部キー[Books(user.id),reviews(user.id,book.id),readingPlans(user.id)]

- DBテスト
　DBに保存されているか、DBから削除されているか

- 認可テスト(Policy)
　許可された人以外はその操作ができないか

- 認証テスト
　許可された人以外はその操作ができないか


## バリデーションテスト

Request
1.BookRequest
2.GenreRequest
3.ReviewRequest

正常系
バリデーションが正常に働いているか、異常な値ははじかれるか
フラッシュメッセージが表示されるか



