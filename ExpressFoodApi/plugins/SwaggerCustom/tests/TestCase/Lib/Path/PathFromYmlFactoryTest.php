<?php

namespace SwaggerCustom\Test\TestCase\Lib\Path;

use Cake\TestSuite\TestCase;
use SwaggerCustom\Lib\OpenApi\Path;
use SwaggerCustom\Lib\Path\PathFromYmlFactory;

class PathFromYmlFactoryTest extends TestCase
{
    public $fixtures = [
        'plugin.SwaggerCustom.Employees',
    ];

    public function testCreatePath()
    {
        $path = (new PathFromYmlFactory())->create('/pets', [
            'summary' => 'pet summary',
            'description' => 'lorem ipsum description'
        ]);
        $this->assertInstanceOf(Path::class, $path);
        $this->assertEquals('/pets', $path->getResource());
    }
}