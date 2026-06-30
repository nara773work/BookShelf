//リダイレクト先
    正常系
        会員登録：books.index
        書籍登録：books.index
        書籍更新：books.index
        書籍削除：books.index
        レビュー登録：books.show
        レビュー更新：books.show
        レビュー削除：books.show
        ジャンル登録：genre.index
        ジャンル更新：genre.index
        ジャンル削除：genre.index
        toggle(お気に入り登録・削除)：back(books.show)

    異常系
    　　　ジャンル削除→genre.index

    バリデーションエラー時
    　　　エラーメッセージを表示し元のフォームに戻す