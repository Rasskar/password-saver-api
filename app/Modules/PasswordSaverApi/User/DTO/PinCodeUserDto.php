<?php

namespace App\Modules\PasswordSaverApi\User\DTO;

use App\Models\User;
use App\Modules\Infrastructure\DTO\DtoInterface;

class PinCodeUserDto implements DtoInterface
{
    /**
     * @param User $user
     * @param int $pinCode
     * @param int|null $oldPinCode
     */
    public function __construct(
        public User $user,
        public int $pinCode,
        public ?int $oldPinCode = null
    )
    {
    }

    /**
     * @return int[]
     */
    public function toArray(): array
    {
        return [
            'pin_code' => $this->pinCode
        ];
    }
}
