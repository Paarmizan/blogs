<?php

declare(strict_types=1);

namespace Blog\Domain\Repository;

use Blog\Domain\Entity\Post;

interface PostRepositoryInterface
{
    /**
     * @return array{posts: Post[], total: int}
     */
    public function findByCategoryPaginated(int $categoryId, string $sortBy, int $page, int $perPage): array;

    public function findById(int $postId): ?Post;

    public function incrementViews(int $postId): void;

    /**
     * @return Post[]
     */
    public function findSimilar(int $postId, int $limit): array;
}
