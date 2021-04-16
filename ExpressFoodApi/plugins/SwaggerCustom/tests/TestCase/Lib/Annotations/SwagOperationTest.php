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

class SwagOperationTest extends TestCase
{
    public $fixtures = [
        'plugin.SwaggerCustom.Employees',
    ];

    /**
     * @var Router
     */
    private $router;

    /**
     * @var array
     */
    private $config;

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
            $builder->resources('Operations', [
                'map' => [
                    'isVisible' => [
                        'action' => 'isVisible',
                        'method' => 'GET',
                        'path' => 'is-visible'
                    ],
                    'tagNames' => [
                        'action' => 'tagNames',
                        'method' => 'GET',
                        'path' => 'tag-names'
                    ],
                ]
            ]);
            $builder->resources('Departments', function (RouteBuilder $routes) {
                $routes->resources('DepartmentEmployees');
            });
            $builder->resources('EmployeeSalaries');
        });
        $this->router = $router;

        $this->config = [
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
        ];

        if (!$this->swagger instanceof Swagger) {
            $configuration = new Configuration($this->config, SWAGGER_BAKE_TEST_APP);
            $cakeRoute = new RouteScanner($this->router, $configuration);
            $this->swagger = new Swagger(new ModelScanner($cakeRoute, $configuration));
        }

        AnnotationLoader::load();
    }

    public function testIsVisible()
    {
        $arr = json_decode($this->swagger->toString(), true);
        $this->assertArrayNotHasKey('/operations/is-visible', $arr['paths']);
    }

    public function testTagsNames()
    {
        $arr = json_decode($this->swagger->toString(), true);
        $this->assertCount(4, $arr['paths']['/operations/tag-names']['get']['tags']);
    }
}