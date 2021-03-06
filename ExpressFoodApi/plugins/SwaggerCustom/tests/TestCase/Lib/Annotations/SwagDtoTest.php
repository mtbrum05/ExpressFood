<?php


namespace SwaggerCustom\Test\TestCase\Lib\Annotations;

use Cake\Routing\Router;
use Cake\Routing\RouteBuilder;
use Cake\TestSuite\TestCase;
use SwaggerCustom\Lib\AnnotationLoader;
use SwaggerCustom\Lib\Model\ModelScanner;
use SwaggerCustom\Lib\Route\RouteScanner;
use SwaggerCustom\Lib\Configuration;
use SwaggerCustom\Lib\Swagger;

class SwagDtoTest extends TestCase
{
    public $fixtures = [
        'plugin.SwaggerCustom.Employees',
    ];

    private $router;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $router = new Router();
        $router::scope('/', function (RouteBuilder $builder) {
            $builder->setExtensions(['json']);
            $builder->resources('Employees', [
                'only' => ['dtoPost','dtoQuery'],
                'map' => [
                    'dtoPost' => [
                        'action' => 'dtoPost',
                        'method' => 'POST',
                        'path' => 'dto-post'
                    ],
                    'dtoQuery' => [
                        'action' => 'dtoQuery',
                        'method' => 'GET',
                        'path' => 'dto-query'
                    ],
                ]
            ]);
        });
        $this->router = $router;

        $this->config = new Configuration([
            'prefix' => '/',
            'yml' => '/config/swagger-bare-bones.yml',
            'json' => '/webroot/swagger.json',
            'webPath' => '/swagger.json',
            'hotReload' => false,
            'exceptionSchema' => 'Exception',
            'requestAccepts' => ['application/x-www-form-urlencoded'],
            'responseContentTypes' => ['application/json'],
            'namespaces' => [
                'controllers' => ['\SwaggerCustomTest\App\\'],
                'entities' => ['\SwaggerCustomTest\App\\'],
                'tables' => ['\SwaggerCustomTest\App\\'],
            ]
        ], SWAGGER_BAKE_TEST_APP);

        AnnotationLoader::load();
    }

    public function testSwagDtoQuery()
    {
        $cakeRoute = new RouteScanner($this->router, $this->config);

        $swagger = new Swagger(new ModelScanner($cakeRoute, $this->config));
        $arr = json_decode($swagger->toString(), true);

        $operation = $arr['paths']['/employees/dto-query']['get'];

        $this->assertEquals('firstName', $operation['parameters'][0]['name']);
        $this->assertEquals('lastName', $operation['parameters'][1]['name']);
        $this->assertEquals('title', $operation['parameters'][2]['name']);
        $this->assertEquals('age', $operation['parameters'][3]['name']);
        $this->assertEquals('date', $operation['parameters'][4]['name']);
    }

    public function testSwagDtoPost()
    {
        $cakeRoute = new RouteScanner($this->router, $this->config);

        $swagger = new Swagger(new ModelScanner($cakeRoute, $this->config));
        $arr = json_decode($swagger->toString(), true);

        $operation = $arr['paths']['/employees/dto-post']['post'];
        $properties = $operation['requestBody']['content']['application/x-www-form-urlencoded']['schema']['properties'];

        $this->assertArrayHasKey('lastName', $properties);
        $this->assertArrayHasKey('firstName', $properties);
        $this->assertArrayHasKey('title', $properties);
        $this->assertArrayHasKey('age', $properties);
        $this->assertArrayHasKey('date', $properties);
    }
}