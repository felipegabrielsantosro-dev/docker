<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Installment extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('parcela', ['id' => false,'primary_key' => ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true,'null' => false])
            ->addColumn('venda_id', 'biginteger', ['null' => false])
            ->addColumn('numero_parcela', 'integer', ['null' => false])
            ->addColumn('valor', 'decimal', ['precision' => 10,'scale' => 2,'null' => false ])
            ->addColumn('data_vencimento', 'date', ['null' => false])
            ->addColumn('paga', 'boolean', ['default' => false])
            ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('data_atualizacao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
