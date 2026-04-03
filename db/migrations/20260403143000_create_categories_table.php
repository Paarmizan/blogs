<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCategoriesTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('categories')
            ->addColumn('name', 'string', ['limit' => 150])
            ->addColumn('description', 'text')
            ->addTimestamps()
            ->addIndex(['name'], ['unique' => true])
            ->create();
    }
}
