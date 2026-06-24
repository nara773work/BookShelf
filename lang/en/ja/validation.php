<?php
return [

    'required' => ':attribute を入力してください',

    'password' => [
        'letters' => ':attribute には少なくとも1文字の英字を含めてください。',
        'mixed' => ':attribute には大文字と小文字を含めてください。',
        'numbers' => ':attribute には少なくとも1つの数字を含めてください。',
        'symbols' => ':attribute には少なくとも1つの記号を含めてください。',
        'uncompromised' => '入力された :attribute は安全ではありません。',
    ],

    'attributes' => [
        'name' => '名前',
        'email' => 'メールアドレス',
        'password' => 'パスワード',
    ],

];