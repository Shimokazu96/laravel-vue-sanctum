<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'    => ['required', 'between:1,10', 'unique:users'],
            'email'    => ['required', 'email', 'unique:users'],
            'password' => ['required', 'between:6,30', 'confirmed'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $res = response()->json([
            'status' => Response::HTTP_BAD_REQUEST,
            'errors' => $validator->errors(),
        ], 422);
        throw new HttpResponseException($res);
    }
}
