<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Customer extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('cliente', ['id' => false,'primary_key' => ['id']]);
        $table->addColumn('id', 'biginteger', [ 'identity' => true,'null' => false])
            ->addColumn('nome', 'string', ['limit' => 150,'null' => false])
            ->addColumn('cpf_cnpj', 'string', ['limit' => 18,'null' => false])
            ->addColumn('telefone', 'string', ['limit' => 20,'null' => true])
            ->addColumn('email', 'string', ['limit' => 150,'null' => true])
            ->addColumn('endereco', 'string', ['limit' => 255,'null' => true])
            ->addColumn('ativo', 'boolean', ['default' => true])
            ->addColumn('data_cadastro', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('data_atualizacao', 'datetime', ['null' => true, 'default' => 'CURRENT_TIMESTAMP'])
            ->create();
    }
}
