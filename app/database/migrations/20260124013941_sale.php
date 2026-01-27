<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Sale extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('payment_terms', ['id' => false,'primary_key' => ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true,'null' => true])
            ->addColumn('description', 'string', ['limit' => 100,'null' => true])
            ->addColumn('installments', 'integer', ['null' => true,'default' => 1])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->create();
    }
}
