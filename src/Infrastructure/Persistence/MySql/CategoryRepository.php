<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Persistence\MySql;

use Blog\Domain\Entity\Category;
use Blog\Domain\Entity\Post;
use Blog\Domain\Repository\CategoryRepositoryInterface;
use PDO;

final class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function findCategoriesWithPosts(int $postsPerCategory): array
    {
        $limit = max(1, $postsPerCategory);

        $categories = $this->pdo->query(
            'SELECT c.id, c.name, c.description
             FROM categories c
             WHERE EXISTS (
                 SELECT 1
                 FROM post_categories pc
                 WHERE pc.category_id = c.id
             )
             ORDER BY c.name ASC'
        )->fetchAll();

        $postsStmt = $this->pdo->prepare(
            "SELECT p.id, p.image, p.title, p.description, p.content, p.views_count, p.published_at
             FROM posts p
             INNER JOIN post_categories pc ON pc.post_id = p.id
             WHERE pc.category_id = :category_id
             ORDER BY p.published_at DESC
             LIMIT {$limit}"
        );

        $sections = [];
        foreach ($categories as $categoryRow) {
            $postsStmt->execute(['category_id' => (int) $categoryRow['id']]);
            $postRows = $postsStmt->fetchAll();

            $sections[] = [
                'category' => $this->mapCategory($categoryRow),
                'posts' => array_map(fn (array $row) => $this->mapPost($row), $postRows),
            ];
        }

        return $sections;
    }

    public function findById(int $id): ?Category
    {
        $stmt = $this->pdo->prepare('SELECT id, name, description FROM categories WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if ($row === false) {
            return null;
        }

        return $this->mapCategory($row);
    }

    private function mapCategory(array $row): Category
    {
        return new Category(
            (int) $row['id'],
            (string) $row['name'],
            (string) $row['description']
        );
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
}
