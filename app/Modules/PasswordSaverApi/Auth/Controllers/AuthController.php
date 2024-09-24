<?php

namespace App\Modules\PasswordSaverApi\Auth\Controllers;

use App\Exceptions\SaveException;
use App\Http\Controllers\Controller;
use App\Modules\PasswordSaverApi\Auth\Actions\LoginUserAction;
use App\Modules\PasswordSaverApi\Auth\Actions\RegisterUserAction;
use App\Modules\PasswordSaverApi\Auth\DTO\LoginUserDto;
use App\Modules\PasswordSaverApi\Auth\DTO\RegisterUserDto;
use App\Modules\PasswordSaverApi\Auth\Requests\LoginRequest;
use App\Modules\PasswordSaverApi\Auth\Requests\RegisterUserRequest;
use App\Modules\PasswordSaverApi\Auth\Resources\AuthUserResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/v1/password-saver-api/register",
     *      summary="Регистрация пользователя",
     *      tags={"Auth"},
     *      operationId="registerUserPasswordSaverApi",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Данные для регистрации пользователя",
     *          @OA\JsonContent(
     *              required={"name","email","password","password_confirmation"},
     *              @OA\Property(property="name", type="string", example="TestName", description="Имя пользователя, должно быть уникальным"),
     *              @OA\Property(property="email", type="string", example="test@gmail.com", description="Email пользователя, должен быть уникальным"),
     *              @OA\Property(property="password", type="string", format="password", example="Test1234", description="Пароль должен содержать минимум 8 символов, включать буквы в верхнем и нижнем регистрах, а также цифры"),
     *              @OA\Property(property="password_confirmation", type="string", format="password", example="Test1234", description="Подтверждение пароля должно совпадать с паролем")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Успешный ответ",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example=1, description="ID зарегистрированного пользователя"),
     *                  @OA\Property(property="name", type="string", example="TestName", description="Имя пользователя"),
     *                  @OA\Property(property="email", type="string", example="test@gmail.com", description="Email пользователя"),
     *                  @OA\Property(property="token", type="string", example="1|0DNmmQtybPzvK2Gp50HnsGUI3JUp2iXVMunfmlb04cc5aa33", description="Bearer токен для авторизации")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Ошибка валидации",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Текст ошибки валидации")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Внутренняя ошибка сервера",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Текст ошибки сервера")
     *          )
     *      )
     * )
     *
     * @param RegisterUserRequest $request
     * @param RegisterUserAction $action
     * @return AuthUserResource|JsonResponse
     */
    public function register(RegisterUserRequest $request, RegisterUserAction $action)
    {
        $registerUserDto = new RegisterUserDto(...$request->only('name', 'email', 'password'));

        try {
            $authUserDto = $action->run($registerUserDto);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }

        return new AuthUserResource($authUserDto);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/password-saver-api/login",
     *      summary="Вход пользователя",
     *      tags={"Auth"},
     *      operationId="loginUserPasswordSaverApi",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Данные для входа пользователя",
     *          @OA\JsonContent(
     *              required={"email", "password"},
     *              @OA\Property(property="email", type="string", format="email", example="test@gmail.com", description="Email пользователя"),
     *              @OA\Property(property="password", type="string", format="password", example="Test1234", description="Пароль пользователя")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Успешный ответ",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example=1, description="ID пользователя"),
     *                  @OA\Property(property="name", type="string", example="TestName", description="Имя пользователя"),
     *                  @OA\Property(property="email", type="string", example="test@gmail.com", description="Email пользователя"),
     *                  @OA\Property(property="token", type="string", example="2|1oDxpQzgmAXCHiRzGaBjobeF83wMoqzN77DdThpzbc4846db", description="Токен для авторизации")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Ошибка авторизации",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Ошибка авторизации")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Ошибка валидации",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Текст ошибки валидации")
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Внутренняя ошибка сервера",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Ошибка сервера")
     *          )
     *      )
     * )
     *
     * @param LoginRequest $request
     * @param LoginUserAction $action
     * @return AuthUserResource|JsonResponse
     */
    public function login(LoginRequest $request, LoginUserAction $action)
    {
        $loginUserDto = new LoginUserDto(...$request->only('email', 'password'));

        try {
            $authUserDto = $action->run($loginUserDto);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }

        return new AuthUserResource($authUserDto);
    }

    /**
     * @OA\Get(
     *      path="/api/v1/password-saver-api/logout",
     *      summary="Выход пользователя",
     *      tags={"Auth"},
     *      operationId="logoutUserPasswordSaverApi",
     *      @OA\Response(
     *          response=200,
     *          description="Успешный ответ",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Успешно вышел из системы", description="Сообщение об успешном выходе")
     *          )
     *      ),
     *      @OA\Response(
     *           response=401,
     *           description="Ошибка аутентификации",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Текст ошибки", description="Пользователь не аутентифицирован")
     *           )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Внутренняя ошибка сервера",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Ошибка сервера", description="Сообщение об ошибке при удалении токена")
     *          )
     *      ),
     *      security={{"bearerAuth":{}}}
     * )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            if (!$request->user()->currentAccessToken()->delete()) {
                throw new SaveException('Error deleting token when logging out.');
            }

            return response()->json(['message' => 'Successfully logged out'], Response::HTTP_OK);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }
    }
}
