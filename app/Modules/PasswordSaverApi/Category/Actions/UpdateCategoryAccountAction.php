<?php

namespace App\Modules\PasswordSaverApi\Category\Actions;

use App\Exceptions\AccessDeniedException;
use App\Exceptions\SaveException;
use App\Models\CategoryAccount;
use App\Modules\PasswordSaverApi\Category\DTO\CategoryAccountDto;
use Exception;

/**
 * Обновляем категорию акаунтов пользоваетля
 */
class UpdateCategoryAccountAction
{
    /**
     * @param CategoryAccount $categoryAccount
     * @param CategoryAccountDto $categoryAccountDto
     * @return CategoryAccount
     * @throws AccessDeniedException
     * @throws SaveException
     */
    public function run(CategoryAccount $categoryAccount, CategoryAccountDto $categoryAccountDto)
    {
        try {
            if ($categoryAccountDto->userId != $categoryAccount->user_id) {
                throw new AccessDeniedException('The user cannot update a category that does not belong to them.');
            }

            if (!$categoryAccount->update($categoryAccountDto->toArray())) {
                throw new SaveException('Failed to update category.');
            }

            return $categoryAccount;
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
