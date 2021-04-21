<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\EnderecoEmpresaTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\EnderecoEmpresaTable Test Case
 */
class EnderecoEmpresaTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\EnderecoEmpresaTable
     */
    protected $EnderecoEmpresa;

    /**
     * Fixtures
     *
     * @var array
     */
    protected $fixtures = [
        'app.EnderecoEmpresa',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('EnderecoEmpresa') ? [] : ['className' => EnderecoEmpresaTable::class];
        $this->EnderecoEmpresa = $this->getTableLocator()->get('EnderecoEmpresa', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->EnderecoEmpresa);

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
