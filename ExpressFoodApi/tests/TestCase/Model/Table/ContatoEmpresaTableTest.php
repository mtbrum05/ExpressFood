<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ContatoEmpresaTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ContatoEmpresaTable Test Case
 */
class ContatoEmpresaTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ContatoEmpresaTable
     */
    protected $ContatoEmpresa;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.ContatoEmpresa',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('ContatoEmpresa') ? [] : ['className' => ContatoEmpresaTable::class];
        $this->ContatoEmpresa = $this->getTableLocator()->get('ContatoEmpresa', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->ContatoEmpresa);

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
