<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' =>['required','max:150',],
            'author'=>['required','max:100','string'],
            'isbn'=>['required','digits:13',
                    Rule::unique('books', 'isbn')
                    ->ignore($this->book),],
            'published_date'=>['required'],
            'description'=>['nullable'], //説明の中で数字を使う可能性を考慮し文字列のみにしない
            'image_url',
            'genres'=>['required']
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'タイトルを入力してください',
            'title.max'=>'150字以内で入力してください',
            'author.required' => '著者を入力してください',
            'author.max'=>'100字以内で入力してください',
            'author.string'=>'文字列で入力してください',
            'isbn.required' => 'isbnコードを入力してください',
            'isbn.digits'=>'13字で入力してください',
            'isbn.unique'=>'そのisbnコードは既に存在しています',
            'published_date.required' => '出版日を入力してください',
            'genres.required' => 'ジャンルを選択してください',
        ];
    }
}
