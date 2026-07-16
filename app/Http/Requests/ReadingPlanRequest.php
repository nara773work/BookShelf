<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if ($this->isMethod('post')) {
            return [
                'book_id' => ['exists:books,id',
                    Rule::unique('reading_plans')
                        ->where(function ($query) {
                            return $query->where('user_id', auth()->id());
                        }),
                ],
                'target_date' => ['required', 'after_or_equal:today'],
            ];
        }

        return [
            'target_date' => ['required'],
        ];
    }

    public function messages(): array
    {
        if ($this->isMethod('post')) {
            return [
                'book_id.required' => '書籍を選択してください',
                'target_date.required' => '期日を選択してください',
                'target_date.after_or_equal' => '本日以降の日付を選択してください',
                'book_id.unique' => 'この書籍の計画は既に存在しています',
            ];
        }

        return [
            'book_id.required' => '書籍を選択してください',
            'target_date.required' => '期日を選択してください',
        ];
    }
}
