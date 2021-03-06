<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ContatoClienteFixture
 */
class ContatoClienteFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'contato_cliente';
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'descricao' => ['type' => 'string', 'length' => 15, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'data_criacao' => ['type' => 'timestamp', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => ''],
        'data_modificacao' => ['type' => 'timestamp', 'length' => null, 'precision' => null, 'null' => true, 'default' => 'current_timestamp()', 'comment' => ''],
        'codigo_contato' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'codigo_cliente' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ativo' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null],
        '_indexes' => [
            'codigo_cliente' => ['type' => 'index', 'columns' => ['codigo_cliente'], 'length' => []],
            'codigo_usuario_fk' => ['type' => 'index', 'columns' => ['codigo_contato'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'contato_cliente_ibfk_1' => ['type' => 'foreign', 'columns' => ['codigo_cliente'], 'references' => ['cliente', 'codigo'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'codigo_usuario_fk' => ['type' => 'foreign', 'columns' => ['codigo_contato'], 'references' => ['contato', 'codigo'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // phpcs:enable
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'codigo' => 1,
                'descricao' => 'Lorem ipsum d',
                'data_criacao' => 1619032190,
                'data_modificacao' => 1619032190,
                'codigo_contato' => 1,
                'codigo_cliente' => 1,
                'ativo' => 1,
            ],
        ];
        parent::init();
    }
}
