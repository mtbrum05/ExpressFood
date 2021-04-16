<?php
declare(strict_types=1);

namespace SwaggerCustom\Lib\Model;

use Cake\Collection\Collection;
use Cake\Core\Configure;
use Cake\Database\Connection;
use Cake\Datasource\ConnectionManager;
use MixerApi\Core\Model\Model;
use MixerApi\Core\Model\ModelFactory;
use MixerApi\Core\Utility\NamespaceUtility;
use SwaggerCustom\Lib\Annotation\SwagEntity;
use SwaggerCustom\Lib\Configuration;
use SwaggerCustom\Lib\Exception\SwaggerCustomRunTimeException;
use SwaggerCustom\Lib\Route\RouteDecorator;
use SwaggerCustom\Lib\Route\RouteScanner;
use SwaggerCustom\Lib\Utility\AnnotationUtility;

/**
 * Finds all Entities associated with RESTful routes based on userland configurations
 */
class ModelScanner
{
    /**
     * @var \SwaggerCustom\Lib\Route\RouteScanner
     */
    private $routeScanner;

    /**
     * @var string
     */
    private $prefix;

    /**
     * @var \SwaggerCustom\Lib\Configuration
     */
    private $config;

    /**
     * @param \SwaggerCustom\Lib\Route\RouteScanner $routeScanner RouteScanner
     * @param \SwaggerCustom\Lib\Configuration $config Configuration
     */
    public function __construct(RouteScanner $routeScanner, Configuration $config)
    {
        $this->routeScanner = $routeScanner;
        $this->prefix = $config->getPrefix();
        $this->config = $config;
    }

    /**
     * Gets an array of ModelDecorator instances
     *
     * @return \SwaggerCustom\Lib\Model\ModelDecorator[]
     */
    public function getModelDecorators(): array
    {
        $return = [];

        $connection = ConnectionManager::get('default');

        if (!$connection instanceof Connection) {
            throw new SwaggerCustomRunTimeException('Unable to get Database Connection instance');
        }

        $tables = NamespaceUtility::findClasses(Configure::read('App.namespace') . '\Model\Table');

        foreach ($tables as $table) {
            try {
                $model = (new ModelFactory($connection, new $table()))->create();
            } catch (\Exception $e) {
                continue;
            }

            if ($model === null) {
                continue;
            }

            $routeDecorator = $this->getRouteDecorator($model);
            if (!$this->hasVisibility($model, $routeDecorator)) {
                continue;
            }

            if ($routeDecorator) {
                $controllerFqn = $routeDecorator->getControllerFqn();
                $controller = $controllerFqn ? new $controllerFqn() : null;
            }

            $return[] = new ModelDecorator($model, $controller ?? null);
        }

        return $return;
    }

    /**
     * @return \SwaggerCustom\Lib\Route\RouteScanner
     */
    public function getRouteScanner(): RouteScanner
    {
        return $this->routeScanner;
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @return \SwaggerCustom\Lib\Configuration
     */
    public function getConfig(): Configuration
    {
        return $this->config;
    }

    /**
     * @param \MixerApi\Core\Model\Model $model Model instance
     * @return \SwaggerCustom\Lib\Route\RouteDecorator
     */
    private function getRouteDecorator(Model $model): ?RouteDecorator
    {
        $routes = $this->routeScanner->getRoutes();

        $result = (new Collection($routes))->filter(function (RouteDecorator $route) use ($model) {
            return $route->getController() == $model->getTable()->getAlias();
        });

        return $result->first();
    }

    /**
     * @param \MixerApi\Core\Model\Model $model Model instance
     * @param \SwaggerCustom\Lib\Route\RouteDecorator|null $routeDecorator RouteDecorator instance
     * @return bool
     */
    private function hasVisibility(Model $model, ?RouteDecorator $routeDecorator): bool
    {
        $annotations = AnnotationUtility::getClassAnnotationsFromFqns(get_class($model->getEntity()));

        $swagEntities = array_filter($annotations, function ($annotation) {
            return $annotation instanceof SwagEntity;
        });

        if (empty($swagEntities)) {
            return $routeDecorator !== null;
        }

        $swagEntity = reset($swagEntities);

        return $swagEntity->isVisible;
    }
}
