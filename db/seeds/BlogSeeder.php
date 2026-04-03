<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

final class BlogSeeder extends AbstractSeed
{
    public function run(): void
    {
        $adapter = $this->getAdapter();
        $now = date('Y-m-d H:i:s');

        $adapter->execute('SET FOREIGN_KEY_CHECKS=0');
        $adapter->execute('TRUNCATE TABLE post_categories');
        $adapter->execute('TRUNCATE TABLE posts');
        $adapter->execute('TRUNCATE TABLE categories');
        $adapter->execute('SET FOREIGN_KEY_CHECKS=1');

        $categories = [
            [
                'name' => 'Category 1',
                'description' => 'Актуальные публикации про тренды, визуальную культуру и современный digital.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Category 2',
                'description' => 'Практические материалы о разработке, инструментах и командных процессах.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Category 3',
                'description' => 'Истории, интервью и кейсы о росте продуктов и пользовательском опыте.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Category 4',
                'description' => 'Разборы и аналитика по контенту, метрикам и эффективности публикаций.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $this->table('categories')->insert($categories)->saveData();

        $images = [
            'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1512496015851-a90fb38ba796?auto=format&fit=crop&w=1200&q=80',
        ];

        $posts = [];
        for ($i = 1; $i <= 18; $i++) {
            $posts[] = [
                'image' => $images[$i % count($images)],
                'title' => sprintf('Статья %d: как выстроить контент-ритм', $i),
                'description' => 'Короткий анонс статьи с основными тезисами и пользой для читателя.',
                'content' => "Это полный текст статьи №{$i}.\n\nМы рассматриваем структуру материала, формат подачи и примеры, которые помогают поддерживать интерес аудитории.\n\nВ завершении даем чек-лист для внедрения в реальном проекте.",
                'views_count' => 20 + ($i * 17),
                'published_at' => date('Y-m-d H:i:s', strtotime("-{$i} days")),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $this->table('posts')->insert($posts)->saveData();

        $categoryRows = $adapter->fetchAll('SELECT id FROM categories ORDER BY id ASC');
        $postRows = $adapter->fetchAll('SELECT id FROM posts ORDER BY id ASC');

        $categoryIds = array_map(static fn (array $row): int => (int) $row['id'], $categoryRows);
        $postIds = array_map(static fn (array $row): int => (int) $row['id'], $postRows);

        $links = [];
        foreach ($postIds as $index => $postId) {
            $firstCategory = $categoryIds[$index % count($categoryIds)];
            $secondCategory = $categoryIds[($index + 1) % count($categoryIds)];

            $links[] = [
                'post_id' => $postId,
                'category_id' => $firstCategory,
            ];

            if ($index % 2 === 0) {
                $links[] = [
                    'post_id' => $postId,
                    'category_id' => $secondCategory,
                ];
            }
        }

        $this->table('post_categories')->insert($links)->saveData();
    }
}
