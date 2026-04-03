<?php

declare(strict_types=1);

namespace Blog\Application\GetCategoryPage;

use Blog\Application\GetCategoryPage\DTO\RequestDTO;
use Blog\Application\GetCategoryPage\DTO\ResponseDTO;
use Blog\Domain\Entity\Post;
use Blog\Domain\Repository\CategoryRepositoryInterface;
use Blog\Domain\Repository\PostRepositoryInterface;

final class GetCategoryPageService
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly PostRepositoryInterface $postRepository
    ) {
    }

    public function handle(RequestDTO $dto): ?ResponseDTO
    {
        $sortBy = $dto->sortBy->value;
        $page = max(1, $dto->page);
        $perPage = max(1, $dto->perPage);

        $category = $this->categoryRepository->findById($dto->categoryId);
        if ($category === null) {
            return null;
        }

        $result = $this->postRepository->findByCategoryPaginated($category->getId(), $sortBy, $page, $perPage);
        $total = $result['total'];
        $totalPages = max(1, (int) ceil($total / $perPage));
        $currentPage = min($page, $totalPages);

        return new ResponseDTO(
            [
                'id' => $category->getId(),
                'name' => $category->getName(),
                'description' => $category->getDescription(),
            ],
            array_map(fn (Post $post) => $this->mapPostCard($post), $result['posts']),
            $sortBy,
            [
                'currentPage' => $currentPage,
                'perPage' => $perPage,
                'total' => $total,
                'totalPages' => $totalPages,
                'hasPrev' => $currentPage > 1,
                'hasNext' => $currentPage < $totalPages,
                'prevPage' => max(1, $currentPage - 1),
                'nextPage' => min($totalPages, $currentPage + 1),
            ]
        );
    }

    private function mapPostCard(Post $post): array
    {
        return [
            'id' => $post->getId(),
            'image' => $post->getImage(),
            'title' => $post->getTitle(),
            'description' => $post->getDescription(),
            'publishedAt' => date('F j, Y', strtotime($post->getPublishedAt())),
            'viewsCount' => $post->getViewsCount(),
        ];
    }
}
