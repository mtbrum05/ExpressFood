<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EnderecoClienteTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EnderecoClienteTable Test Case
 */
class EnderecoClienteTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\EnderecoClienteTable
     */
    protected $EnderecoCliente;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.EnderecoCliente',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('EnderecoCliente') ? [] : ['className' => EnderecoClienteTable::class];
        $this->EnderecoCliente = $this->getTableLocator()->get('EnderecoCliente', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->EnderecoCliente);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
