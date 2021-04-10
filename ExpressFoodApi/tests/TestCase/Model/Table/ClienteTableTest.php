<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ClienteTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ClienteTable Test Case
 */
class ClienteTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ClienteTable
     */
    protected $Cliente;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Cliente',
        'app.Contato',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Cliente') ? [] : ['className' => ClienteTable::class];
        $this->Cliente = $this->getTableLocator()->get('Cliente', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Cliente);

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
