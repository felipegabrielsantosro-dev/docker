<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class InstallmentSale extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('installment_sale', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true, 'null' => false])
            ->addColumn('venda_id', 'text', ['null' => true])
            ->addColumn('numero_parcela', 'text', ['null' => true])
            ->addColumn('data_vencimento', 'text', ['null' => true])
            ->addColumn('data_pagamento', 'text', ['null' => true])
            ->addColumn('ativo', 'boolean', ['null' => true])
            ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('data_atualizacao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}