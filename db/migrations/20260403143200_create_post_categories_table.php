<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreatePostCategoriesTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('post_categories', ['id' => false, 'primary_key' => ['post_id', 'category_id']])
            ->addColumn('post_id', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('category_id', 'integer', ['null' => false, 'signed' => false])
            ->addForeignKey('post_id', 'posts', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->addForeignKey('category_id', 'categories', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->create();
    }
}
