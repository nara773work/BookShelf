<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => '評価を選択してください',
            'rating.max' => '星1～星5の範囲で選択してください',
            'rating.min' => '星1～星5の範囲で選択してください',
            'comment.max' => '255字以内で入力してください',
        ];
    }
}
