<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ItemSale extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('item_venda', ['id' => false,'primary_key' => ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true,'null' => false])
        ->addColumn('venda_id', 'biginteger', ['null' => true])
        ->addColumn('produto_id', 'biginteger', ['null' => true])
        ->addColumn('quantidade', 'integer', ['null' => true])
        ->addColumn('valor_unitario', 'decimal', ['precision' => 10,'scale' => 2,'null' => true])
        ->addColumn('valor_total', 'decimal', ['precision' => 10,'scale' => 2,'null' => true])
        ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
        ->addColumn('data_atualizacao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
        ->create();
    }
}
 