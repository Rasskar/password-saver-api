<?php

namespace App\Modules\PasswordSaverApi\Category\DTO;

use App\Modules\Infrastructure\DTO\DtoInterface;

class CategoryAccountDto implements DtoInterface
{
    public function __construct(
        public int $userId,
        public string $name,
        public ?string $description = null
    )
    {
    }
    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'name' => $this->name,
            'description' => $this->description
        ];
    }
}
