<?php

namespace Modules\PasswordSaverApi\User\Controllers;

use App\Exceptions\SaveException;
use App\Models\User;
use App\Modules\PasswordSaverApi\Auth\Actions\RegisterUserAction;
use App\Modules\PasswordSaverApi\Auth\DTO\RegisterUserDto;
use App\Modules\PasswordSaverApi\User\Controllers\UserController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест успешного ответа получения информации о пользователе
     * @return void
     * @throws SaveException
     */
    public function testSuccessfulUserInfo()
    {
        $registerUserDto = new RegisterUserDto(
            'TestName100',
            'test100@gmail.com',
            'Test1234'
        );

        $authUserDto = app(RegisterUserAction::class)->run($registerUserDto);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $authUserDto->token,
        ])->getJson('/api/v1/password-saver-api/user');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'active',
                    'token'
                ],
            ]);
    }

    /**
     * Тест успешной установки пинкода пользователю
     * @return void
     * @throws SaveException
     */
    public function testSuccessfulUserSetPinCode()
    {
        $registerUserDto = new RegisterUserDto(
            'TestName100',
            'test100@gmail.com',
            'Test1234'
        );

        $authUserDto = app(RegisterUserAction::class)->run($registerUserDto);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $authUserDto->token,
        ])->putJson('/api/v1/password-saver-api/user/setPinCode', [
            'pin_code' => 1234
        ]);

        $response->assertStatus(200)->assertJsonStructure(['message']);
    }

    /**
     * Тест успешного обновления пинкода пользователю
     * @return void
     */
    public function testSuccessfulUserUpdatePinCode()
    {
        $user = User::factory()->create([
            'name' => 'TestName100',
            'email' => 'test100@gmail.com',
            'password' => 'Test1234',
            'pin_code' => 1234
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user->createToken('password-saver-api')->plainTextToken,
        ])->putJson('/api/v1/password-saver-api/user/updatePinCode', [
            'pin_code' => 4321,
            'old_pin_code' => 1234
        ]);

        $response->assertStatus(200)->assertJsonStructure(['message']);
    }
}
