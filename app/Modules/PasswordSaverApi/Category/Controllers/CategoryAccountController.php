<?php

namespace App\Modules\PasswordSaverApi\Category\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CategoryAccount;
use App\Modules\PasswordSaverApi\Category\Actions\DeleteCategoryAccountAction;
use App\Modules\PasswordSaverApi\Category\Actions\ListCategoryAccountAction;
use App\Modules\PasswordSaverApi\Category\Actions\StoreCategoryAccountAction;
use App\Modules\PasswordSaverApi\Category\Actions\UpdateCategoryAccountAction;
use App\Modules\PasswordSaverApi\Category\DTO\CategoryAccountDto;
use App\Modules\PasswordSaverApi\Category\Requests\CategoryAccountRequest;
use App\Modules\PasswordSaverApi\Category\Resources\CategoryAccountResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class CategoryAccountController extends Controller
{
    /**
     * @OA\Post(
     *       path="/api/v1/password-saver-api/categories",
     *       summary="Создание категории",
     *       tags={"Category"},
     *       operationId="storeCategoryPasswordSaverApi",
     *       @OA\RequestBody(
     *           required=true,
     *           description="Данные для создания категории",
     *           @OA\JsonContent(
     *               required={"name"},
     *               @OA\Property(property="name", type="string", example="Test Category Name", description="Название категории, максимальная длина 255 символов"),
     *               @OA\Property(property="description", type="string", example="Test category description", description="Описание категории, не является обязательным")
     *           )
     *       ),
     *       @OA\Response(
     *           response=200,
     *           description="Категория успешно создана",
     *           @OA\JsonContent(
     *               @OA\Property(property="data", type="object",
     *                   @OA\Property(property="id", type="integer", example=1, description="ID созданной категории"),
     *                   @OA\Property(property="name", type="string", example="Test Category Name ", description="Название категории"),
     *                   @OA\Property(property="description", type="string", example="Test category description", description="Описание категории")
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *            response=401,
     *            description="Ошибка аутентификации",
     *            @OA\JsonContent(
     *                @OA\Property(property="message", type="string", example="Текст ошибки", description="Пользователь не аутентифицирован")
     *            )
     *       ),
     *       @OA\Response(
     *           response=422,
     *           description="Ошибка валидации",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Текст ошибки валидации")
     *           )
     *       ),
     *       @OA\Response(
     *           response=500,
     *           description="Внутренняя ошибка сервера",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Ошибка сервера")
     *           )
     *       ),
     *       security={{"bearerAuth":{}}},
     * )
     *
     * Создаём категорию
     * @param CategoryAccountRequest $request
     * @param StoreCategoryAccountAction $action
     * @return CategoryAccountResource|JsonResponse
     */
    public function store(CategoryAccountRequest $request, StoreCategoryAccountAction $action)
    {
        $categoryAccountDto = new CategoryAccountDto(
            $request->user()->id,
            $request->input('name'),
            $request->input('description')
        );

        try {
            $categoryAccount = $action->run($categoryAccountDto);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }

        return new CategoryAccountResource($categoryAccount);
    }

    /**
     * @OA\Put(
     *       path="/api/v1/password-saver-api/categories/{id}",
     *       summary="Обновление категории",
     *       tags={"Category"},
     *       operationId="updateCategoryPasswordSaverApi",
     *       description="Обновление существующей категории пользователя",
     *       @OA\Parameter(
     *           name="id",
     *           in="path",
     *           required=true,
     *           description="ID категории для обновления",
     *           @OA\Schema(type="integer", example=1)
     *       ),
     *       @OA\RequestBody(
     *           required=true,
     *           description="Данные для обновления категории",
     *           @OA\JsonContent(
     *               required={"name"},
     *               @OA\Property(property="name", type="string", example="Test category name", description="Название категории, максимальная длина 255 символов"),
     *               @OA\Property(property="description", type="string", example="Test category description", description="Описание категории, не является обязательным")
     *           )
     *       ),
     *       @OA\Response(
     *           response=200,
     *           description="Категория успешно обновлена",
     *           @OA\JsonContent(
     *               @OA\Property(property="data", type="object",
     *                   @OA\Property(property="id", type="integer", example=1, description="ID категории"),
     *                   @OA\Property(property="name", type="string", example="Test category name", description="Название категории"),
     *                   @OA\Property(property="description", type="string", example="Test category description", description="Описание категории")
     *               )
     *           )
     *       ),
     *       @OA\Response(
     *            response=401,
     *            description="Ошибка аутентификации",
     *            @OA\JsonContent(
     *                @OA\Property(property="message", type="string", example="Текст ошибки", description="Пользователь не аутентифицирован")
     *            )
     *       ),
     *       @OA\Response(
     *           response=422,
     *           description="Ошибка валидации",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Поле name обязательно для заполнения.")
     *           )
     *       ),
     *       @OA\Response(
     *           response=500,
     *           description="Внутренняя ошибка сервера",
     *           @OA\JsonContent(
     *               @OA\Property(property="message", type="string", example="Ошибка сервера")
     *           )
     *       ),
     *       security={{"bearerAuth":{}}}
     * )
     *
     * Обновляем категорию
     * @param CategoryAccountRequest $request
     * @param CategoryAccount $categoryAccount
     * @param UpdateCategoryAccountAction $action
     * @return CategoryAccountResource|JsonResponse
     */
    public function update(
        CategoryAccountRequest $request,
        CategoryAccount $categoryAccount,
        UpdateCategoryAccountAction $action
    )
    {
        $categoryAccountDto = new CategoryAccountDto(
            $request->user()->id,
            $request->input('name'),
            $request->input('description')
        );

        try {
            $categoryAccount = $action->run($categoryAccount, $categoryAccountDto);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }

        return new CategoryAccountResource($categoryAccount);
    }

    /**
     * Удаление категории
     * @param Request $request
     * @param CategoryAccount $categoryAccount
     * @param DeleteCategoryAccountAction $action
     * @return JsonResponse
     */
    public function destroy(
        Request $request,
        CategoryAccount $categoryAccount,
        DeleteCategoryAccountAction $action
    )
    {
        try {
            $action->run($request->user(), $categoryAccount);
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }

        return response()->json(['message' => 'Successfully delete category'], Response::HTTP_OK);
    }

    /**
     * Просмотр категории
     * @param CategoryAccount $categoryAccount
     * @return CategoryAccountResource
     */
    public function show(CategoryAccount $categoryAccount)
    {
        return new CategoryAccountResource($categoryAccount);
    }

    /**
     * Просмотр список категорий пользователя
     * @param Request $request
     * @param ListCategoryAccountAction $action
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function index(Request $request, ListCategoryAccountAction $action)
    {
        try {
            $categoriesAccounts = $action->run($request->user());
        } catch (Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], $exception->getCode());
        }

        return CategoryAccountResource::collection($categoriesAccounts);
    }
}
