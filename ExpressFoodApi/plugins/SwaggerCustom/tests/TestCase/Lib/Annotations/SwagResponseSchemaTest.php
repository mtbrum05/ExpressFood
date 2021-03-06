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

class SwagResponseSchemaTest extends TestCase
{
    public $fixtures = [
        'plugin.SwaggerCustom.Employees',
    ];

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Swagger
     */
    private $swagger;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $router = new Router();
        $router::scope('/', function (RouteBuilder $builder) {
            $builder->setExtensions(['json']);
            $builder->resources('Employees', [
                'map' => [
                    'customResponseSchema' => [
                        'action' => 'customResponseSchema',
                        'method' => 'GET',
                        'path' => 'custom-response-schema'
                    ],
                    'schemaItems' => [
                        'action' => 'schemaItems',
                        'method' => 'GET',
                        'path' => 'schema-items'
                    ],
                ]
            ]);
        });
        $this->router = $router;

        $this->config = new Configuration([
            'prefix' => '/',
            'yml' => '/config/swagger-with-existing.yml',
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

        if (!$this->swagger instanceof Swagger) {
            $cakeRoute = new RouteScanner($this->router, $this->config);
            $this->swagger = new Swagger(new ModelScanner($cakeRoute, $this->config));
        }


        AnnotationLoader::load();
    }

    public function testSwagResponseSchema()
    {
        $arr = json_decode($this->swagger->toString(), true);

        $operation = $arr['paths']['/employees/custom-response-schema']['get'];

        $schema = $operation['responses']['200']['content']['application/json']['schema'];
        $this->assertEquals('#/components/schemas/Pet', $schema['$ref']);

        $this->assertArrayHasKey('400', $operation['responses']);
        $this->assertEquals('deprecated httpCode still works', $operation['responses']['400']['description']);

        $this->assertArrayHasKey('404', $operation['responses']);
        $this->assertEquals('new statusCode', $operation['responses']['404']['description']);

        $this->assertArrayHasKey('5XX', $operation['responses']);
        $this->assertEquals('status code range', $operation['responses']['5XX']['description']);
    }

    public function testSchemaItems()
    {
        $arr = json_decode($this->swagger->toString(), true);

        $operation = $arr['paths']['/employees/schema-items']['get'];

        $schema = $operation['responses']['200']['content']['application/json']['schema'];

        $this->assertEquals('array', $schema['type']);
        $this->assertEquals('#/components/schemas/Pet', $schema['items']['$ref']);
    }
}