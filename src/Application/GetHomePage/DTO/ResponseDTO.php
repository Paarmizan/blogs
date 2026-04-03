<?php

declare(strict_types=1);

namespace Blog\Application\GetHomePage\DTO;

final class ResponseDTO
{
    /**
     * @param array<int, array{category: array, posts: array}> $sections
     */
    public function __construct(
        public readonly array $sections
    ) {
    }
}
