<?php

namespace SwaggerCustom\Test\TestCase\Lib\Path;

use Cake\TestSuite\TestCase;
use SwaggerCustom\Lib\OpenApi\Operation;
use SwaggerCustom\Lib\Operation\OperationFromYmlFactory;

class OperationFromYmlFactoryTest extends TestCase
{
    public $fixtures = [
        'plugin.SwaggerCustom.Employees',
    ];

    public function testCreatePath()
    {
        $operation = (new OperationFromYmlFactory())->create('GET', [
            'tags' => ['hello'],
            'operationId' => 'operation:id',
            'deprecated' => false
        ]);
        $this->assertInstanceOf(Operation::class, $operation);
    }
}