<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Persistence\MySql;

use Blog\Domain\Entity\Category;
use Blog\Domain\Entity\Post;
use Blog\Domain\Repository\CategoryRepositoryInterface;
use PDO;

final class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    public function findCategoriesWithPosts(int $postsPerCategory): array
    {
        $limit = max(1, $postsPerCategory);
        $sql = <<<SQL
            SELECT
                c.id AS category_id,
                c.name AS category_name,
                c.description AS category_description,
                ranked_posts.id,
                ranked_posts.image,
                ranked_posts.title,
                ranked_posts.description,
                ranked_posts.content,
                ranked_posts.views_count,
                ranked_posts.published_at
            FROM categories c
            INNER JOIN (
                SELECT
                    pc.category_id,
                    p.id,
                    p.image,
                    p.title,
                    p.description,
                    p.content,
                    p.views_count,
                    p.published_at,
                    ROW_NUMBER() OVER (
                        PARTITION BY pc.category_id
                        ORDER BY p.published_at DESC, p.id DESC
                    ) AS row_num
                FROM post_categories pc
                INNER JOIN posts p ON p.id = pc.post_id
            ) ranked_posts ON ranked_posts.category_id = c.id
            WHERE ranked_posts.row_num <= {$limit}
            ORDER BY c.name ASC, ranked_posts.published_at DESC, ranked_posts.id DESC
        SQL;

        $rows = $this->pdo->query($sql)->fetchAll();

        $sectionsByCategoryId = [];
        foreach ($rows as $row) {
            $categoryId = (int) $row['category_id'];

            if (!isset($sectionsByCategoryId[$categoryId])) {
                $sectionsByCategoryId[$categoryId] = [
                    'category' => $this->mapCategoryFromJoinedRow($row),
                    'posts' => [],
                ];
            }

            $sectionsByCategoryId[$categoryId]['posts'][] = $this->mapPost($row);
        }

        return array_values($sectionsByCategoryId);
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

    private function mapCategoryFromJoinedRow(array $row): Category
    {
        return new Category(
            (int) $row['category_id'],
            (string) $row['category_name'],
            (string) $row['category_description']
        );
    }
}
