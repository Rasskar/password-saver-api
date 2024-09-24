<?php

namespace App\Modules\PasswordSaverApi\Category\Actions;

use App\Exceptions\AccessDeniedException;
use App\Exceptions\SaveException;
use App\Models\CategoryAccount;
use App\Models\User;
use Exception;

/**
 * Удаляем категорию пользователя если категория не прикреплена к аккаунтам
 */
class DeleteCategoryAccountAction
{
    /**
     * @param User $user
     * @param CategoryAccount $categoryAccount
     * @return void
     * @throws AccessDeniedException
     * @throws SaveException
     */
    public function run(User $user, CategoryAccount $categoryAccount)
    {
        try {
            if ($user->id != $categoryAccount->user_id) {
                throw new AccessDeniedException('The user cannot delete a category that does not belong to them.');
            }

            // Нужно добавить проверку после того как появяться акаунты что нельзя удалить категорию пока категория не пустая

            if (!$categoryAccount->delete()) {
                throw new SaveException('Failed to delete category.');
            }
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
