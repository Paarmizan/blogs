<?php

declare(strict_types=1);

namespace Blog\Application\GetCategoryPage\DTO;

final class ResponseDTO
{
    public function __construct(
        public readonly array $category,
        public readonly array $posts,
        public readonly string $sortBy,
        public readonly array $pagination
    ) {
    }
}
