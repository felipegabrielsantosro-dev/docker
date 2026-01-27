<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Product extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('produto', ['id' => false,'primary_key' => ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true,'null' => false])
            ->addColumn('nome', 'string', ['limit' => 150,'null' => true])
            ->addColumn('descricao', 'string', ['limit' => 255,'null' => true])
            ->addColumn('preco', 'decimal', ['precision' => 10,'scale' => 2,'null' => true])
            ->addColumn('estoque', 'integer', ['default' => 0,'null' => true])
            ->addColumn('ativo', 'boolean', ['default' => true])
            ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('data_atualizacao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
