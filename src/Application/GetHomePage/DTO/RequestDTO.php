<?php

declare(strict_types=1);

namespace Blog\Application\GetHomePage\DTO;

final class RequestDTO
{
    public function __construct(
        public readonly int $postsPerCategory = 3
    ) {
    }
}
