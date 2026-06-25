<?php

namespace App\Http\Requests;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rating' => ['required'],
            'comment'=> ['max:255']
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => '評価を入力してください',
            'comment.max'=>'255字以内で入力してください',
        ];
    }

}
