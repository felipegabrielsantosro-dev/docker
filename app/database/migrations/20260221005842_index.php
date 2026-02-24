<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Index extends AbstractMigration
{
   
    public function change(): void
    {
        $this->execute("
        CREATE EXTENSION IF NOT EXISTS pg_trgm;
        
        create index idx_id_customer on customer (id);

        CREATE INDEX idx_nome_fantasia_customer ON customer USING gin (nome_fantasia gin_trgm_ops);
        
        CREATE INDEX idx_sobrenome_razao_customer ON customer USING gin (sobrenome_razao gin_trgm_ops);
        ");
    }
}