<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        Validator::make(
            $input, [
                'name' => ['required', 'max:50', 'string'],
                'email' => [
                    'required',
                    'email',
                    'max:100',
                    Rule::unique(User::class),
                ],

                'password' => $this->passwordRules(), ],
            [
                'name.required' => '名前を入力してください',
                'name.max' => '50字以内で入力してください',
                'name.string' => '文字列で入力してください',

                'email.required' => 'メールアドレスを入力してください',
                'email.max' => '100字以内で入力してください',
                'email.email' => 'メール形式で入力してください',
                'email.unique' => 'このメールアドレスは既に使われています',

                'password.required' => 'パスワードを入力してください',
                'password.max' => '8字以上20字以内で入力してください',
                'password.min' => '8字以上20字以内で入力してください',
                'password.confirmed' => '確認用パスワードが一致しません',
            ]
        )->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
