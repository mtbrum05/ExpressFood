<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ContatoTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ContatoTable Test Case
 */
class ContatoTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ContatoTable
     */
    protected $Contato;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.Contato',
        'app.Cliente',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Contato') ? [] : ['className' => ContatoTable::class];
        $this->Contato = $this->getTableLocator()->get('Contato', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Contato);

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
