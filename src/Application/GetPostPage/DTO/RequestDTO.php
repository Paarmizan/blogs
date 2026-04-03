<?php

declare(strict_types=1);

namespace Blog\Application\GetPostPage\DTO;

final class RequestDTO
{
    public function __construct(
        public readonly int $postId
    ) {
    }
}
