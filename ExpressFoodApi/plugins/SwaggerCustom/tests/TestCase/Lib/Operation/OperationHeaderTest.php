<?php

namespace SwaggerCustom\Test\TestCase\Lib\Operation;

use Cake\TestSuite\TestCase;
use SwaggerCustom\Lib\Annotation\SwagHeader;
use SwaggerCustom\Lib\OpenApi\Operation;
use SwaggerCustom\Lib\Operation\OperationHeader;

class OperationHeaderTest extends TestCase
{
    public function testGetOperationWithHeaders()
    {
        $operation = (new OperationHeader())
            ->getOperationWithHeaders(
                new Operation(),
                [
                    new SwagHeader([
                        'name' => 'X-HEADER',
                        'type' => 'string',
                        'description' => 'test desc',
                        'required' => true,
                        'explode' => true,
                        'allowEmptyValue' => true,
                        'deprecated' => true,
                        'format' => 'date',
                        'example' => 'test example'
                    ])
                ]
            );

        $param = $operation->getParameterByTypeAndName('header', 'X-HEADER');

        $this->assertEquals('X-HEADER', $param->getName());
        $this->assertEquals('header', $param->getIn());
        $this->assertEquals('test desc', $param->getDescription());
        $this->assertTrue($param->isRequired());
        $this->assertTrue($param->isExplode());
        $this->assertTrue($param->isDeprecated());
        $this->assertEquals('test example', $param->getExample());
        $schema = $param->getSchema();
        $this->assertEquals('date', $schema->getFormat());
    }
}