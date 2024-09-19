<?php

namespace App\Modules\PasswordSaverApi\Auth\Actions;

use App\Exceptions\AuthException;
use App\Exceptions\SaveException;
use App\Modules\PasswordSaverApi\Auth\DTO\AuthUserDto;
use App\Modules\PasswordSaverApi\Auth\DTO\LoginUserDto;
use Exception;
use Illuminate\Support\Facades\Auth;

class LoginUserAction
{
    public function run(LoginUserDto $loginUserDto): AuthUserDto
    {
        try {
            if (!Auth::attempt($loginUserDto->toArray())) {
                throw new AuthException("Authorization error, check your email or password");
            }

            $user = Auth::user();

            if ($user->tokens()->where('name', 'password-saver-api')->count() >= 5) {
                $user->tokens()->where('name', 'password-saver-api')->first()->delete();
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
