<?php

namespace SwaggerCustom\Test\TestCase\Command;

use Cake\Core\Configure;
use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;

class RouteCommandTest extends TestCase
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
        $this->exec('swagger routes');
        $this->assertOutputContains('SwaggerCustom is checking your routes...');
        $this->assertOutputContains('departments:index');
    }

    public function testExecuteNoRoutesFoundErrorMessage()
    {
        $this->exec('swagger routes --prefix /nope');
        $this->assertExitError();
    }
}