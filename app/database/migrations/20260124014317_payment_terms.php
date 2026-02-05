<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class PaymentTerms extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('payment_terms', ['id' => false,'primary_key' => ['id'],]);
        $table->addColumn('id', 'biginteger', ['identity' => true,'signed' =>true,])
            ->addColumn('codigo', 'string', ['limit' => 50,'null' => true,])
            ->addColumn('titulo', 'string', ['limit' => 255,'null' => true,])
            ->addColumn('quantidade_parcelas', 'integer', ['null' => true,])
            ->addColumn('intervalo_dias', 'integer', ['null' => true,])
            ->addColumn('ativo', 'boolean', ['default' => true,'null' => true,])
            ->addColumn('excluido', 'boolean', ['default' => true,'null' => true,])
            ->addColumn('data_cadastro', 'datetime', ['default' => 'CURRENT_TIMESTAMP','null' => true,])
            ->addColumn('data_atualizacao', 'datetime', ['default' => 'CURRENT_TIMESTAMP','update' => 'CURRENT_TIMESTAMP','null' => true,])
            ->addIndex(['codigo'], ['unique' => true,'name' => 'idx_payment_terms_codigo',])
            ->create();
    }
}

