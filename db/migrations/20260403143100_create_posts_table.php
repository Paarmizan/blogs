<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreatePostsTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('posts')
            ->addColumn('image', 'string', ['limit' => 500])
            ->addColumn('title', 'string', ['limit' => 200])
            ->addColumn('description', 'text')
            ->addColumn('content', 'text')
            ->addColumn('views_count', 'integer', ['default' => 0])
            ->addColumn('published_at', 'datetime')
            ->addTimestamps()
            ->addIndex(['published_at'])
            ->addIndex(['views_count'])
            ->create();
    }
}
