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
use Symfony\Component\HttpFoundation\Response;
use Exception;

class CategoryAccountController extends Controller
{
    /**
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
