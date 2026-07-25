<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reset</title>
</head>
<body>

    <form method="POST" action="{{ route('password.update') }}">
    @csrf

    <input type="hidden" name="token" value="{{ request()->route('token') }}">

    <label for="">メールアドレス</label>
    <input type="email" name="email" value="{{ request('email') }}">

    <label for="">新しいパスワード</label>
    <input type="password" name="password">

    <label for="">確認</label>
    <input type="password" name="password_confirmation">

    <button>
        パスワード変更
    </button>
</form>
</body>
</html>