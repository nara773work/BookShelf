<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>forget</title>
</head>
<body>
    <form method="POST" action="{{ route('password.email') }}">
    @csrf

    <label for="">登録されているメールアドレスを入力してください</label><br>
    <input type="email" name="email">

    <button>
        送信
    </button>
</form>
</body>
</html>