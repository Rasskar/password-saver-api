<?php

namespace App\Modules\PasswordSaverApi\User\Actions;

use App\Exceptions\SaveException;
use App\Exceptions\ValidationApiException;
use App\Modules\PasswordSaverApi\User\DTO\PinCodeUserDto;
use Exception;

class SetPinCodeUserAction
{
    /**
     * @param PinCodeUserDto $pinCodeUserDto
     * @return void
     * @throws SaveException
     * @throws ValidationApiException
     */
    public function run(PinCodeUserDto $pinCodeUserDto): void
    {
        try {
            $user = $pinCodeUserDto->user;

            if (!empty($user->pin_code)) {
                throw new ValidationApiException('User PIN code has already been set.');
            }

            if (!$user->update($pinCodeUserDto->toArray())) {
                throw new SaveException('Failed to set PIN code.');
            }
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
