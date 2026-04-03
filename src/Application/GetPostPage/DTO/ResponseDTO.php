<?php

declare(strict_types=1);

namespace Blog\Application\GetPostPage\DTO;

final class ResponseDTO
{
    public function __construct(
        public readonly array $post,
        public readonly array $similarPosts
    ) {
    }
}
