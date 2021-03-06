<?php

use Phinx\Migration\AbstractMigration;

class Contato extends AbstractMigration
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
        $table = $this->table('contato', ['id' => 'codigo']);
        
        $table->addColumn('descricao', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => false,
        ]);
        $table->addIndex(['descricao'], ['unique' => true]);
        
        $table->addColumn('data_criacao', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP',
            'null' => false,
        ]);
        $table->addColumn('data_modificacao', 'timestamp', [
            'default' => 'CURRENT_TIMESTAMP',
            'null' => true,
        ]);
        $table->addColumn('ativo', 'boolean', [
            'signed' => true,
            'default' => true,
            'null' => false,
        ]);
        
        $table->create();
    }
}
