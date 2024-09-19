<?php

namespace App\Modules\PasswordSaverApi\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()],
        ];
    }
}
