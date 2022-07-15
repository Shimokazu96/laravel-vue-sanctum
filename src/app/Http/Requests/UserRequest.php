<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'required',
            // 'email'                   => ['required', 'email', Rule::unique('users')->ignore($this->user)],
            'tel'         => ['nullable', 'numeric', 'digits_between:8,11'],
            'zip'         => ['nullable', 'numeric'],
            'image'       => ['nullable', 'file','max:3072','mimes:jpg,jpeg,png'],
        ];
    }
    /**
     *  バリデーション項目名定義
     * @return array
     */
    public function attributes()
    {
        return [
            'tel'         => '電話番号',
            'zip'         => '郵便番号',
            'image'       => 'アイコン',
        ];
    }
}
