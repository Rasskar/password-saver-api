<?php

namespace App\Modules\PasswordSaverApi\Auth\DTO;

use App\Modules\Infrastructure\DTO\DtoInterface;

class LoginUserDto implements DtoInterface
{
    /**
     * @param string $email
     * @param string $password
     */
    public function __construct(
        public string $email,
        public string $password
    )
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password
        ];
    }
}
