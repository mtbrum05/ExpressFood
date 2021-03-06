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

class SwagRequestBodyContentTest extends TestCase
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
            $builder->resources('SwagRequestBodyContent', [
                'map' => [
                    'textPlain' => [
                        'action' => 'textPlain',
                        'method' => 'POST',
                        'path' => 'text-plain'
                    ],
                    'multipleMimeTypes' => [
                        'action' => 'multipleMimeTypes',
                        'method' => 'POST',
                        'path' => 'multiple-mime-types'
                    ],
                    'useConfigDefaults' => [
                        'action' => 'useConfigDefaults',
                        'method' => 'POST',
                        'path' => 'use-config-defaults'
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
            'requestAccepts' => ['application/x-www-form-urlencoded','application/xml','application/json'],
            'responseContentTypes' => ['application/json'],
            'namespaces' => [
                'controllers' => ['\SwaggerCustomTest\App\\'],
                'entities' => ['\SwaggerCustomTest\App\\'],
                'tables' => ['\SwaggerCustomTest\App\\'],
            ]
        ], SWAGGER_BAKE_TEST_APP);

        AnnotationLoader::load();
    }

    public function testSwagRequestBodyContent()
    {
        $cakeRoute = new RouteScanner($this->router, $this->config);

        $swagger = new Swagger(new ModelScanner($cakeRoute, $this->config));
        $arr = json_decode($swagger->toString(), true);

        $operation = $arr['paths']['/swag-request-body-content/text-plain']['post'];

        $this->assertArrayHasKey('schema', $operation['requestBody']['content']['text/plain']);
    }

    public function testMultipleMimeTypes()
    {
        $cakeRoute = new RouteScanner($this->router, $this->config);

        $swagger = new Swagger(new ModelScanner($cakeRoute, $this->config));
        $arr = json_decode($swagger->toString(), true);

        $operation = $arr['paths']['/swag-request-body-content/multiple-mime-types']['post'];

        $this->assertCount(2, $operation['requestBody']['content']);
    }

    public function testUseMimeTypesFromConfig()
    {
        $cakeRoute = new RouteScanner($this->router, $this->config);

        $swagger = new Swagger(new ModelScanner($cakeRoute, $this->config));
        $arr = json_decode($swagger->toString(), true);

        $operation = $arr['paths']['/swag-request-body-content/use-config-defaults']['post'];

        $this->assertCount(3, $operation['requestBody']['content']);
    }
}