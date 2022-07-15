<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\AlphaNumHalf;
use App\Models\Admin;
use Illuminate\Http\Request;

class AdminRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $validation = 'nullable';
        if($request->password || $request->password_confirmation) {
            $validation = ['required', 'string', new AlphaNumHalf, 'confirmed'];
        }
        return [
            'name'        => ['required', 'string', 'max:30'],
            'email'       => ['required','string','email','max:255',Rule::unique('admins')->ignore($this->id)],
            'password'    => $validation,
        ];
    }
}
