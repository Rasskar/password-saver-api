<?php

namespace App\Modules\PasswordSaverApi\Category\Actions;

use App\Exceptions\NotFoundException;
use App\Models\CategoryAccount;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Получаем список категорий пользователя
 */
class ListCategoryAccountAction
{
    /**
     * @param User $user
     * @return LengthAwarePaginator
     * @throws NotFoundException
     */
    public function run(User $user): LengthAwarePaginator
    {
        try {
            $categoriesAccounts = CategoryAccount::query()
                ->where('user_id', $user->id)
                ->orderBy('id', 'asc')
                ->paginate(2);

            if (empty($categoriesAccounts->total())) {
                throw new NotFoundException('No categories found for the user.');
            }

            return $categoriesAccounts;
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
