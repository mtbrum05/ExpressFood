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

class SwagSecurityTest extends TestCase
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
                'map' => [
                    'customGet' => [
                        'action' => 'customGet',
                        'method' => 'GET',
                        'path' => 'custom-get'
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

    public function testSwagHeader()
    {
        $cakeRoute = new RouteScanner($this->router, $this->config);

        $swagger = new Swagger(new ModelScanner($cakeRoute, $this->config));
        $arr = json_decode($swagger->toString(), true);


        $this->assertArrayHasKey('/employees/custom-get', $arr['paths']);
        $this->assertArrayHasKey('get', $arr['paths']['/employees/custom-get']);
        $operation = $arr['paths']['/employees/custom-get']['get'];

        $this->assertEquals('custom-get summary', $operation['summary']);

        $this->assertCount(1, array_filter($operation['security'], function ($param) {
            return isset($param['BearerAuth']);
        }));
    }
}