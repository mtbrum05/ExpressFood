<?php

namespace SwaggerCustom\Test\TestCase\Command;

use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;

class ModelCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    public $fixtures = [
        'plugin.SwaggerCustom.Departments'
    ];

    public function setUp() : void
    {
        parent::setUp();
        $this->setAppNamespace('SwaggerCustomTest\App');
        $this->useCommandRunner();
    }

    public function testExecute()
    {
        $this->exec('swagger models');
        $this->assertOutputContains('SwaggerCustom is checking your models...');
        $this->assertOutputContains('- Department');
        $this->assertOutputContains('id');
        $this->assertOutputContains('name');
    }

    public function testExecuteNoModelsFoundErrorMessage()
    {
        $this->markTestSkipped('Difficulty testing since #224');
        $this->exec('swagger models --prefix /nope');
        $this->assertExitError();
    }
}