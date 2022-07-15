<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Admin;

class AdminVerifyEmailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $admin = Admin::where('id',  $this->route('id'))->first();
        if (! hash_equals((string) $this->route('id'), (string) $admin->getKey())) {
            return false;
        }

        if (! hash_equals((string) $this->route('hash'), sha1($admin->getEmailForVerification()))) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
