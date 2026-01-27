<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class PaymentTerms extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('condicao_pagamento', ['id' => false,'primary_key' => ['id']]);
        $table->addColumn('id', 'biginteger', ['identity' => true,'null' => false])
            ->addColumn('descricao', 'string', ['limit' => 100,'null' => false])
            ->addColumn('prazo_dias', 'integer', ['default' => 0,'null' => false])
            ->addColumn('parcelas', 'integer', ['default' => 1,'null' => false])
            ->addColumn('ativo', 'boolean', ['default' => true])
            ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('data_atualizacao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
