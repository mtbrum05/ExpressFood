<?php

namespace SwaggerCustom\Test\TestCase\Lib\Operation;

use Cake\TestSuite\TestCase;
use Cake\Routing\Route\Route;
use SwaggerCustom\Lib\OpenApi\Operation;
use SwaggerCustom\Lib\Operation\OperationPath;
use SwaggerCustom\Lib\Route\RouteDecorator;

class OperationPathTest extends TestCase
{
    public function testGetOperationWithHeaders()
    {
        $routeDecorator = new RouteDecorator(
            new Route('//employees/:id', [
                '_method' => ['GET'],
                'plugin' => '',
                'controller' => 'Employees',
                'action' => 'view'
            ])
        );

        $operation = (new OperationPath(new Operation(), $routeDecorator))->getOperationWithPathParameters();

        $parameters = $operation->getParameters();
        $param = reset($parameters);
        $this->assertEquals('id', $param->getName());
        $this->assertEquals('path', $param->getIn());
    }
}