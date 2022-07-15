<?php

namespace App\Actions\Admin;

use Laravel\Fortify\Rules\Password;
use App\Rules\AlphaNumHalf;
trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array
     */
    protected function passwordRules()
    {
        return ['required', 'string', new AlphaNumHalf, 'confirmed'];
        // return ['required', 'string', new Password, 'confirmed'];
    }
}
