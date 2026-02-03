<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class PaymentTerms extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('PaymentTerms', ['id' => false,'primary_key' => ['id']]);
         $table->addColumn('id', 'biginteger', ['identity' => true, 'null' => false])
            ->addColumn('codigo', 'text', ['null' => true])
            ->addColumn('titulo', 'text', ['null' => true])
            ->addColumn('quantidade_parcelas', 'text', ['null' => true])
            ->addColumn('intervalo_dias', 'text', ['null' => true])
            ->addColumn('ativo', 'boolean', ['null' => true])
            ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('data_atualizacao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
