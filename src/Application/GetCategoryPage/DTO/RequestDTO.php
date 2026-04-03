<?php

declare(strict_types=1);

namespace Blog\Application\GetCategoryPage\DTO;

use Blog\Application\GetCategoryPage\Enum\SortBy;

final class RequestDTO
{
    public readonly int $categoryId;
    public readonly SortBy $sortBy;
    public readonly int $page;
    public readonly int $perPage;

    public function __construct(
        int $categoryId,
        string $sortBy,
        int $page,
        int $perPage = 6
    ) {
        $this->categoryId = $categoryId;
        $this->sortBy = SortBy::fromRaw($sortBy);
        $this->page = $page;
        $this->perPage = $perPage;
    }
}
