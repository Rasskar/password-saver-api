<?php

namespace App\Modules\PasswordSaverApi\User\Actions;

use App\Exceptions\SaveException;
use App\Exceptions\ValidationApiException;
use App\Modules\PasswordSaverApi\User\DTO\PinCodeUserDto;
use Exception;

class UpdatePinCodeUserAction
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

            if ($user->pin_code != $pinCodeUserDto->oldPinCode) {
                throw new ValidationApiException('The confirmation PIN code must match the current PIN code.');
            }

            if ($user->pin_code == $pinCodeUserDto->pinCode) {
                throw new ValidationApiException('The new PIN code must not be the same as the current PIN code.');
            }

            if (!$user->update($pinCodeUserDto->toArray())) {
                throw new SaveException('Failed to update PIN code.');
            }
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
