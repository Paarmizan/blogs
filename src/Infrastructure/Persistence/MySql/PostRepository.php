<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Persistence\MySql;

use Blog\Domain\Entity\Category;
use Blog\Domain\Entity\Post;
use Blog\Domain\Repository\PostRepositoryInterface;
use PDO;

final class PostRepository implements PostRepositoryInterface
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function findByCategoryPaginated(int $categoryId, string $sortBy, int $page, int $perPage): array
    {
        $countStmt = $this->pdo->prepare(
            'SELECT COUNT(*)
             FROM posts p
             INNER JOIN post_categories pc ON pc.post_id = p.id
             WHERE pc.category_id = :category_id'
        );
        $countStmt->execute(['category_id' => $categoryId]);
        $total = (int) $countStmt->fetchColumn();

        $offset = max(0, ($page - 1) * $perPage);
        $orderBy = $this->resolveSortClause($sortBy);

        $stmt = $this->pdo->prepare(
            "SELECT p.id, p.image, p.title, p.description, p.content, p.views_count, p.published_at
             FROM posts p
             INNER JOIN post_categories pc ON pc.post_id = p.id
             WHERE pc.category_id = :category_id
             ORDER BY {$orderBy}
             LIMIT :limit OFFSET :offset"
        );

        $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $posts = array_map(fn (array $row) => $this->mapPost($row), $stmt->fetchAll());

        return [
            'posts' => $posts,
            'total' => $total,
        ];
    }

    public function findById(int $postId): ?Post
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, image, title, description, content, views_count, published_at
             FROM posts
             WHERE id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $postId]);
        $row = $stmt->fetch();

        if ($row === false) {
            return null;
        }

        $categoryStmt = $this->pdo->prepare(
            'SELECT c.id, c.name, c.description
             FROM categories c
             INNER JOIN post_categories pc ON pc.category_id = c.id
             WHERE pc.post_id = :post_id
             ORDER BY c.name ASC'
        );
        $categoryStmt->execute(['post_id' => $postId]);
        $categories = array_map(fn (array $item) => $this->mapCategory($item), $categoryStmt->fetchAll());

        $post = $this->mapPost($row);
        $post->setCategories($categories);

        return $post;
    }

    public function incrementViews(int $postId): void
    {
        $stmt = $this->pdo->prepare('UPDATE posts SET views_count = views_count + 1 WHERE id = :id');
        $stmt->execute(['id' => $postId]);
    }

    public function findSimilar(int $postId, int $limit): array
    {
        $safeLimit = max(1, $limit);

        $stmt = $this->pdo->prepare(
            "SELECT DISTINCT p2.id, p2.image, p2.title, p2.description, p2.content, p2.views_count, p2.published_at
             FROM post_categories base_pc
             INNER JOIN post_categories related_pc ON related_pc.category_id = base_pc.category_id
             INNER JOIN posts p2 ON p2.id = related_pc.post_id
             WHERE base_pc.post_id = :post_id
               AND p2.id <> :exclude_post_id
             ORDER BY p2.published_at DESC
             LIMIT {$safeLimit}"
        );
        $stmt->execute([
            'post_id' => $postId,
            'exclude_post_id' => $postId,
        ]);

        $similarPosts = array_map(fn (array $row) => $this->mapPost($row), $stmt->fetchAll());

        if (count($similarPosts) >= $safeLimit) {
            return $similarPosts;
        }

        $remaining = $safeLimit - count($similarPosts);
        $excludedIds = array_merge([$postId], array_map(static fn (Post $post): int => $post->getId(), $similarPosts));

        $placeholders = implode(',', array_fill(0, count($excludedIds), '?'));
        $fallbackStmt = $this->pdo->prepare(
            "SELECT id, image, title, description, content, views_count, published_at
             FROM posts
             WHERE id NOT IN ({$placeholders})
             ORDER BY published_at DESC
             LIMIT {$remaining}"
        );
        $fallbackStmt->execute($excludedIds);

        foreach ($fallbackStmt->fetchAll() as $row) {
            $similarPosts[] = $this->mapPost($row);
        }

        return $similarPosts;
    }

    private function resolveSortClause(string $sortBy): string
    {
        return match ($sortBy) {
            'views' => 'p.views_count DESC, p.published_at DESC',
            default => 'p.published_at DESC',
        };
    }

    private function mapPost(array $row): Post
    {
        return new Post(
            (int) $row['id'],
            (string) $row['image'],
            (string) $row['title'],
            (string) $row['description'],
            (string) ($row['content'] ?? ''),
            (int) $row['views_count'],
            (string) $row['published_at']
        );
    }

    private function mapCategory(array $row): Category
    {
        return new Category(
            (int) $row['id'],
            (string) $row['name'],
            (string) $row['description']
        );
    }
}
