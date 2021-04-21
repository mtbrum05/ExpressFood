<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ContatoFixture
 */
class ContatoFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public $table = 'contato';
    /**
     * Fields
     *
     * @var array
     */
    // phpcs:disable
    public $fields = [
        'codigo' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'descricao' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'data_criacao' => ['type' => 'timestamp', 'length' => null, 'precision' => null, 'null' => false, 'default' => 'current_timestamp()', 'comment' => ''],
        'data_modificacao' => ['type' => 'timestamp', 'length' => null, 'precision' => null, 'null' => true, 'default' => 'current_timestamp()', 'comment' => ''],
        'ativo' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1', 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['codigo'], 'length' => []],
            'descricao' => ['type' => 'unique', 'columns' => ['descricao'], 'length' => []],
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
                'descricao' => 'Telefone',
                'data_criacao' => date('Y-m-d H:i:s'),
                'data_modificacao' => date('Y-m-d H:i:s'),
                'ativo' => 1,
            ],
            //registro criado para os testes de delete
            [
                'codigo' => 2,
                'descricao' => 'Outros',
                'data_criacao' => date('Y-m-d H:i:s'),
                'data_modificacao' => date('Y-m-d H:i:s'),
                'ativo' => 0,
            ],
        ];
        parent::init();
    }
}
