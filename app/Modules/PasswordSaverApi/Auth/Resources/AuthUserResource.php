<?php

namespace App\Modules\PasswordSaverApi\Auth\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthUserResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->userId,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'token' => $this->resource->token
        ];
    }
}
