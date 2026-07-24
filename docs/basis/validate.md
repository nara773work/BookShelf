BookRequest
GenreRequest
ReadingPlanRequest
ReviewRequest
FilterRequest

※否定ではなく指示を意識した文言にするために、基本的に「〇〇して下さい」という形に統一する。

## ユーザー新規登録(app/CreateNewUser.php)
    name：必須|max:50|文字列
    必須：名前を入力してください
　  max：50字以内で入力してください
　  文字列：文字列で入力してください

    email：必須|max:100|メール形式|一意性
    必須：メールアドレスを入力してください
    max：100字以内で入力してください
    email：メール形式で入力してください
    unique:このメールアドレスは既に使われています

    password：必須|min:8|max:20|confirmed
    必須：パスワードを入力してください
　  min：8字以上20字以内で入力してください
　  max：8字以上20字以内で入力してください
    confirmed:確認用パスワードが一致しません

## ログイン(lang/en/auth,lang/en/validation)
    email：必須
    必須：メールアドレスを入力してください

　  password：必須
　  必須：パスワードを入力してください

    どちらか間違っている場合：メールアドレス、またはパスワードが正しくありません

## 書籍登録、編集（公開APIも同様）
    title：必須|max:150
    必須：本のタイトルを入力してください
　  max：150字以内で入力してください

    author：必須|max:100
    必須：著者を入力してください
　  max：100字以内で入力してください

    digits：必須|digits13|unique
    必須：isbnコードを入力してください
    digits：13字で入力してください
    unique：そのisbnコードは既に存在しています

    published_date：必須
    必須：出版日を入力してください

    description：任意(text型なので文字制限なし)

    image_url：任意

    genre：必須
　　必須：ジャンルを選択してください

## 検索・フィルター（エラーメッセージなし）
    keyword：nullable|max:255
    genre：nullable|integer|exists:genres,id
    page：nullable|integer|min:1
    per_page：nullable|integer|min:1|max:100

## ジャンル登録、編集（公開APIも同様）
　　name：必須|max:50
　　必須：ジャンル名を入力してください
　　max：50字以内で入力してください

## レビュー登録、編集（公開APIも同様）
　　rating：必須|範囲
　　必須：評価を選択してください
　　範囲：星1～星5の範囲で選択してください

　　comment：任意|max:255
    max：255字以内で入力してください
　　→レビューでは星評価がメイン。
　　 コメントは具体的な内容を示すものなので任意扱いとする。

## 読書計画作成、編集
    book_id：必須|同じ書籍は選べない
    必須：書籍を選択してください

    target_date：必須
    必須：期日を選択してください

## 新規作成時のみ有効
    exists：この書籍の計画は既に存在しています
    target_date.after_or_equal：本日以降の日付を選択してください
               