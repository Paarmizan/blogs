<?php

declare(strict_types=1);

namespace Blog\Application\GetPostPage;

use Blog\Application\GetPostPage\DTO\RequestDTO;
use Blog\Application\GetPostPage\DTO\ResponseDTO;
use Blog\Domain\Entity\Category;
use Blog\Domain\Entity\Post;
use Blog\Domain\Repository\PostRepositoryInterface;

final class GetPostPageService
{
    public function __construct(
        private readonly PostRepositoryInterface $postRepository
    ) {
    }

    public function handle(RequestDTO $dto): ?ResponseDTO
    {
        $post = $this->postRepository->findById($dto->postId);
        if ($post === null) {
            return null;
        }

        $this->postRepository->incrementViews($post->getId());

        $similarPosts = $this->postRepository->findSimilar($post->getId(), 3);

        return new ResponseDTO(
            [
                'id' => $post->getId(),
                'image' => $post->getImage(),
                'title' => $post->getTitle(),
                'description' => $post->getDescription(),
                'content' => $post->getContent(),
                'viewsCount' => $post->getViewsCount() + 1,
                'publishedAt' => date('F j, Y', strtotime($post->getPublishedAt())),
                'categories' => array_map(
                    static fn (Category $category) => [
                        'id' => $category->getId(),
                        'name' => $category->getName(),
                    ],
                    $post->getCategories()
                ),
            ],
            array_map(fn (Post $similarPost) => $this->mapPostCard($similarPost), $similarPosts)
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
