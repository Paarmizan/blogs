<?php

declare(strict_types=1);

namespace Blog\Domain\Entity;

final class Post
{
    /**
     * @param Category[] $categories
     */
    public function __construct(
        private readonly int $id,
        private string $image,
        private string $title,
        private string $description,
        private string $content,
        private int $viewsCount,
        private string $publishedAt,
        private array $categories = []
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getViewsCount(): int
    {
        return $this->viewsCount;
    }

    public function setViewsCount(int $viewsCount): void
    {
        $this->viewsCount = $viewsCount;
    }

    public function getPublishedAt(): string
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(string $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @param Category[] $categories
     */
    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }
}
