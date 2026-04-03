<?php

declare(strict_types=1);

namespace Blog\Application\GetHomePage;

use Blog\Application\GetHomePage\DTO\RequestDTO;
use Blog\Application\GetHomePage\DTO\ResponseDTO;
use Blog\Domain\Entity\Post;
use Blog\Domain\Repository\CategoryRepositoryInterface;

final class GetHomePageService
{
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository
    ) {
    }

    public function handle(RequestDTO $dto): ResponseDTO
    {
        $sections = $this->categoryRepository->findCategoriesWithPosts($dto->postsPerCategory);

        $response = [];
        foreach ($sections as $section) {
            $response[] = [
                'category' => [
                    'id' => $section['category']->getId(),
                    'name' => $section['category']->getName(),
                    'description' => $section['category']->getDescription(),
                ],
                'posts' => array_map(fn (Post $post) => $this->mapPostCard($post), $section['posts']),
            ];
        }

        return new ResponseDTO($response);
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
