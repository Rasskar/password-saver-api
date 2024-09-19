<?php

namespace App\Modules\PasswordSaverApi\Auth\DTO;

use App\Modules\Infrastructure\DTO\DtoInterface;
use Illuminate\Support\Facades\Hash;

class RegisterUserDto implements DtoInterface
{
    /**
     * @param string $name
     * @param string $email
     * @param string $password
     */
    public function __construct(
        public string $name,
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
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password)
        ];
    }
}
