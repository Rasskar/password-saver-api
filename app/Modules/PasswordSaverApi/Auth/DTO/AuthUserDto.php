<?php

namespace App\Modules\PasswordSaverApi\Auth\DTO;

use App\Modules\Infrastructure\DTO\DtoInterface;

class AuthUserDto implements DtoInterface
{
    /**
     * @param int $userId
     * @param string $name
     * @param string $email
     * @param string $token
     */
    public function __construct(
        public int $userId,
        public string $name,
        public string $email,
        public string $token
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'name' => $this->name,
            'email' => $this->email,
            'token' => $this->token
        ];
    }
}
