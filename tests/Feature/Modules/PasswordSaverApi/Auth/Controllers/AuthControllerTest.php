<?php

namespace Tests\Feature\Modules\PasswordSaverApi\Auth\Controllers;

use App\Models\User;
use App\Modules\PasswordSaverApi\Auth\DTO\RegisterUserDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Тест успешной регистрации
     * @return void
     */
    public function testSuccessfulRegistration()
    {
        $response  = $this->postJson('/api/v1/password-saver-api/register', [
            'name' => 'TestName100',
            'email' => 'test100@gmail.com',
            'password' => 'Test1234',
            'password_confirmation' => 'Test1234',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'token',
            ],
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $response['data']['id'],
            'name' => $response['data']['name'],
            'email' => $response['data']['email'],
        ]);
    }

    /**
     * Тест успешного логина
     * @return void
     */
    public function testSuccessfulLogin()
    {
        $registerUserDto = new RegisterUserDto(
            'TestName100',
            'test100@gmail.com',
            'Test1234'
        );

        $user = User::factory()->create($registerUserDto->toArray());

        $response  = $this->postJson('/api/v1/password-saver-api/login', [
            'email' => 'test100@gmail.com',
            'password' => 'Test1234',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'token',
            ],
        ]);

        $this->assertNotNull($user->tokens()->first());
    }

    /**
     * Тест успешного выхода из api
     * @return void
     */
    public function testSuccessfulLogout()
    {
        $registerUserDto = new RegisterUserDto(
            'TestName100',
            'test100@gmail.com',
            'Test1234'
        );

        $user = User::factory()->create($registerUserDto->toArray());

        $token = $user->createToken('password-saver-api')->plainTextToken;;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/password-saver-api/logout');

        $response->assertStatus(200)->assertJsonStructure(['message']);

        $this->assertCount(0, $user->tokens);
    }
}
