<?php

namespace App\Modules\PasswordSaverApi\Category\Actions;

use App\Exceptions\SaveException;
use App\Models\CategoryAccount;
use App\Modules\PasswordSaverApi\Category\DTO\CategoryAccountDto;
use Exception;

/**
 * Создаем категорию для акаунтов
 */
class StoreCategoryAccountAction
{
    /**
     * @param CategoryAccountDto $categoryAccountDto
     * @return CategoryAccount
     * @throws SaveException
     */
    public function run(CategoryAccountDto $categoryAccountDto): CategoryAccount
    {
        try {
            $categoryAccount = CategoryAccount::query()->create($categoryAccountDto->toArray());

            if (!$categoryAccount) {
                throw new SaveException('Failed to create category account.');
            }

            return $categoryAccount;
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
