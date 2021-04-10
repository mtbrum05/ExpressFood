<?php

use Phinx\Migration\AbstractMigration;

class ContatoCliente extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {   
        $table = $this->table('contato_cliente', ['id' => 'codigo']);
        $table->addColumn('descricao', 'string', [
            'default' => null,
            'limit' => 15,
            'null' => false,
        ]);
        $table->addColumn('data_criação', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP',
            'null' => false,
        ]);
        $table->addColumn('data_modificação', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP',
            'null' => true,
        ]);
        $table->addColumn('ativo', 'boolean', [
            'signed' => true,
            'default' => true,
            'null' => false,
        ]);
        $table->addColumn('codigo_cliente', 'integer', [
            'null' => false,
        ]);
        $table->addColumn('codigo_contato', 'integer', [
            'null' => false,
        ]);
        $table->addForeignKey('codigo_cliente', 'cliente', ['codigo'],
                            ['constraint'=>'codigo_cliente_fk']);
        $table->addForeignKey('codigo_contato', 'contato', ['codigo'],
                            ['constraint'=>'codigo_contato_fk']);

        $table->create();
    }
}
