<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'max:150'],
            'author' => ['required', 'max:100'],
            'isbn' => ['required', 'digits:13',
                Rule::unique('books', 'isbn')
                    ->ignore($this->book), ],
            'published_date' => ['nullable'], // APIで取得できない可能性があり、検索等で使用しないためnullableに変更
            'description' => ['nullable'], // 説明の中で数字を使う可能性を考慮し文字列のみにしない
            'image_url' => ['nullable'],
            'genres' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => '本のタイトルを入力してください',
            'title.max' => '150字以内で入力してください',

            'author.required' => '著者を入力してください',
            'author.max' => '100字以内で入力してください',

            'isbn.required' => 'isbnコードを入力してください',
            'isbn.digits' => '13字で入力してください',
            'isbn.unique' => 'そのisbnコードは既に存在しています',

            'genres.required' => 'ジャンルを選択してください',
        ];
    }
}
