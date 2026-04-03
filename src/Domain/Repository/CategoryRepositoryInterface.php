<?php

declare(strict_types=1);

namespace Blog\Domain\Repository;

use Blog\Domain\Entity\Category;

interface CategoryRepositoryInterface
{
    /**
     * @return array<int, array{category: Category, posts: array}> 
     */
    public function findCategoriesWithPosts(int $postsPerCategory): array;

    public function findById(int $id): ?Category;
}
