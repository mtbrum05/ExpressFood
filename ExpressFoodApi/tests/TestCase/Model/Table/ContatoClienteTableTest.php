<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ContatoClienteTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ContatoClienteTable Test Case
 */
class ContatoClienteTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ContatoClienteTable
     */
    protected $ContatoCliente;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.ContatoCliente',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('ContatoCliente') ? [] : ['className' => ContatoClienteTable::class];
        $this->ContatoCliente = $this->getTableLocator()->get('ContatoCliente', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->ContatoCliente);

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
