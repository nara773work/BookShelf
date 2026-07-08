<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReadingPlanRequest extends FormRequest
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
         if ($this->isMethod('post')) {
        return [
            'book_id' => ['required'],
            'target_date' => ['required','after_or_equal:today'],
        ];
    };
     return [
        'target_date' => ['required',],
    ];
    }

    public function messages(): array
    {
        if ($this->isMethod('post')) {
        return [
            'book_id.required' => '書籍を選択してください',
            'target_date.required'=>'期日を選択してください',
            'target_date.after_or_equal'=>'本日以降の日付を選択してください'
        ];
        };
        return [
            'book_id.required' => '書籍を選択してください',
            'target_date.required'=>'期日を選択してください',
        ];
    }
}
