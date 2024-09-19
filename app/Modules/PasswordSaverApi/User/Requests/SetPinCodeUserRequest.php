<?php

namespace App\Modules\PasswordSaverApi\User\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetPinCodeUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'pin_code' => ['required', 'digits:4']
        ];
    }
}
