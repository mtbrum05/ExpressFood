<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ContatoEmpresaFixture
 */
class ContatoEmpresaFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'contato_empresa';
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
        'codigo_empresa' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'ativo' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null],
        '_indexes' => [
            'codigo_empresa' => ['type' => 'index', 'columns' => ['codigo_empresa'], 'length' => []],
            'codigo_contato' => ['type' => 'index', 'columns' => ['codigo_contato'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'contato_empresa_ibfk_2' => ['type' => 'foreign', 'columns' => ['codigo_contato'], 'references' => ['contato', 'codigo'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'contato_empresa_ibfk_1' => ['type' => 'foreign', 'columns' => ['codigo_empresa'], 'references' => ['empresa', 'codigo'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
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
                'data_criacao' => 1619032197,
                'data_modificacao' => 1619032197,
                'codigo_contato' => 1,
                'codigo_empresa' => 1,
                'ativo' => 1,
            ],
        ];
        parent::init();
    }
}
