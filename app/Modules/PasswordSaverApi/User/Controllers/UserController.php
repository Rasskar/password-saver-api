<?php

namespace App\Modules\PasswordSaverApi\User\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\PasswordSaverApi\User\Actions\SetPinCodeUserAction;
use App\Modules\PasswordSaverApi\User\Actions\UpdatePinCodeUserAction;
use App\Modules\PasswordSaverApi\User\DTO\PinCodeUserDto;
use App\Modules\PasswordSaverApi\User\Requests\SetPinCodeUserRequest;
use App\Modules\PasswordSaverApi\User\Requests\UpdatePinCodeUserRequest;
use App\Modules\PasswordSaverApi\User\Resources\UserInfoResource;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/v1/password-saver-api/user",
     *      summary="Получение информации о текущем пользователе",
     *      tags={"User"},
     *      operationId="userInfoPasswordSaverApi",
     *      @OA\Response(
     *          response=200,
     *          description="Успешный ответ",
     *          @OA\JsonContent(
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="id", type="integer", example=1, description="ID пользователя"),
     *                  @OA\Property(property="name", type="string", example="TestName", description="Имя пользователя"),
     *                  @OA\Property(property="email", type="string", example="test@gmail.com", description="Email пользователя"),
     *                  @OA\Property(property="active", type="boolean", example=true, description="Активен ли аккаунт пользователя"),
     *                  @OA\Property(property="token", type="string", example="5|EfiojPaSzOLru1uqi7A9YlA3xggl6tCWkoKHWrVkf3d74066", description="Текущий Bearer токен")
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Ошибка аутентификации",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Текст ошибки", description="Пользователь не аутентифицирован")
     *          )
     *      ),
     *      security={{"bearerAuth":{}}}
     * )
     *
     * @param Request $request
     * @return UserInfoResource
     */
    public function info(Request $request)
    {
        return new UserInfoResource($request->user());
    }

    /**
     * @OA\Put(
     *      path="/api/v1/password-saver-api/user/setPinCode",
     *      summary="Установить PIN-код для пользователя",
     *      tags={"User"},
     *      operationId="setUserPinCodePasswordSaverApi",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Данные для установки PIN-кода",
     *          @OA\JsonContent(
     *              required={"pin_code"},
     *              @OA\Property(property="pin_code", type="integer", example=1234, description="4-значный PIN-код пользователя")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Успешный ответ",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="PIN-код успешно установлен.")
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
     *      ),
     *      security={{"bearerAuth":{}}}
     * )
     *
     * @param SetPinCodeUserRequest $request
     * @param SetPinCodeUserAction $action
     * @return JsonResponse
     */
    public function setPinCode(SetPinCodeUserRequest $request, SetPinCodeUserAction $action)
    {
        $pinCodeUserDto = new PinCodeUserDto(
            $request->user(),
            $request->input('pin_code')
        );

        try {
            $action->run($pinCodeUserDto);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }

        return response()->json(['message' => "PIN code set successfully."], Response::HTTP_OK);
    }

    /**
     * @OA\Put(
     *      path="/api/v1/password-saver-api/user/updatePinCode",
     *      summary="Обновить PIN-код пользователя",
     *      tags={"User"},
     *      operationId="updateUserPinCodePasswordSaverApi",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Данные для обновления PIN-кода",
     *          @OA\JsonContent(
     *              required={"pin_code", "old_pin_code"},
     *              @OA\Property(property="pin_code", type="integer", example=4321, description="Новый 4-значный PIN-код пользователя"),
     *              @OA\Property(property="old_pin_code", type="integer", example=1234, description="Текущий 4-значный PIN-код пользователя для подтверждения")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Успешный ответ",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="PIN-код успешно обнавлён.")
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
     *      ),
     *      security={{"bearerAuth":{}}}
     * )
     *
     * @param UpdatePinCodeUserRequest $request
     * @param UpdatePinCodeUserAction $action
     * @return JsonResponse
     */
    public function updatePinCode(UpdatePinCodeUserRequest $request, UpdatePinCodeUserAction $action)
    {
        $pinCodeUserDto = new PinCodeUserDto(
            $request->user(),
            $request->input('pin_code'),
            $request->input('old_pin_code')
        );

        try {
            $action->run($pinCodeUserDto);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }

        return response()->json(['message' => "PIN code successfully updated."], Response::HTTP_OK);
    }
}
