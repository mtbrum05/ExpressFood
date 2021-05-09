<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ContatoEmpresa extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('contato_empresa', ['id' => 'codigo']);
        $table->addColumn('descricao', 'string', [
            'default' => null,
            'limit' => 15,
            'null' => false,
        ]);
        $table->addColumn('data_criacao', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP',
            'null' => false,
        ]);
        $table->addColumn('data_modificacao', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP',
            'null' => true,
        ]);
        $table->addColumn('codigo_contato', 'integer', [
            'null' => false,
        ]);
        $table->addColumn('codigo_empresa', 'integer', [
            'null' => false,
        ]);
        $table->addColumn('ativo', 'boolean', [
            'signed' => true,
            'default' => true,
            'null' => false,
        ]);
        $table->addForeignKey(
            'codigo_empresa',
            'empresa',
            ['codigo'],
            ['delete'=> 'CASCADE']
        );
        $table->addForeignKey(
            'codigo_contato',
            'contato',
            ['codigo']
        );

        $table->create();
    }
}
