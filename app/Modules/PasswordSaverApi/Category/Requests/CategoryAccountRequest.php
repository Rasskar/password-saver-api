<?php

namespace App\Modules\PasswordSaverApi\Category\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryAccountRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string']
        ];
    }
}
