<?php
declare(strict_types=1);

namespace SwaggerCustom\Lib\Factory;

use Cake\Routing\Router;
use SwaggerCustom\Lib\Configuration;
use SwaggerCustom\Lib\Exception\SwaggerCustomRunTimeException;
use SwaggerCustom\Lib\Model\ModelScanner;
use SwaggerCustom\Lib\Route\RouteScanner;
use SwaggerCustom\Lib\Swagger;
use SwaggerCustom\Lib\Utility\ValidateConfiguration;

/**
 * Class SwaggerFactory
 *
 * @package SwaggerCustom\Lib\Factory
 *
 * Creates an instance of SwaggerCustom\Lib\Swagger
 */
class SwaggerFactory
{
    /**
     * @var \SwaggerCustom\Lib\Configuration
     */
    private $config;

    /**
     * @var \SwaggerCustom\Lib\Route\RouteScanner
     */
    private $routeScanner;

    /**
     * @param \SwaggerCustom\Lib\Configuration|null $config Configuration
     * @param \SwaggerCustom\Lib\Route\RouteScanner|null $routeScanner RouteScanner
     */
    public function __construct(?Configuration $config = null, ?RouteScanner $routeScanner = null)
    {
        $this->config = $config ?? new Configuration();
        ValidateConfiguration::validate($this->config);

        $this->routeScanner = $routeScanner ?? new RouteScanner(new Router(), $this->config);
    }

    /**
     * Creates an instance of Swagger
     *
     * @return \SwaggerCustom\Lib\Swagger
     */
    public function create(): Swagger
    {
        $routes = $this->routeScanner->getRoutes();

        if (empty($routes)) {
            throw new SwaggerCustomRunTimeException(
                'No restful routes were found for your prefix `' . $this->config->getPrefix() . '`. ' .
                'Try adding restful routes to your `config/routes.php`.'
            );
        }

        return new Swagger(new ModelScanner($this->routeScanner, $this->config));
    }
}
