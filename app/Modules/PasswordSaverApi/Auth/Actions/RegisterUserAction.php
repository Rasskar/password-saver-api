<?php

namespace App\Modules\PasswordSaverApi\Auth\Actions;

use App\Exceptions\SaveException;
use App\Models\User;
use App\Modules\PasswordSaverApi\Auth\DTO\AuthUserDto;
use App\Modules\PasswordSaverApi\Auth\DTO\RegisterUserDto;
use Exception;
use Illuminate\Support\Facades\Auth;

class RegisterUserAction
{
    /**
     * @param RegisterUserDto $registerUserDto
     * @return AuthUserDto
     * @throws SaveException
     */
    public function run(RegisterUserDto $registerUserDto): AuthUserDto
    {
        try {
            $user = User::create($registerUserDto->toArray());

            if (!$user) {
                throw new SaveException('Failed to create user');
            }

            $token = $user->createToken('password-saver-api');

            if (!$token) {
                throw new SaveException('Failed to create token');
            }

            return new AuthUserDto(
                $user->id,
                $user->name,
                $user->email,
                $token->plainTextToken
            );
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
