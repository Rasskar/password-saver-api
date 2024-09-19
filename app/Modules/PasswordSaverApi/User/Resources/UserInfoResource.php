<?php

namespace App\Modules\PasswordSaverApi\User\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInfoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'email' => $this->resource->email,
            'active' => (bool) $this->resource->active,
            'token' => $request->bearerToken()
        ];
    }
}
