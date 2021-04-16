<?php
declare(strict_types=1);

namespace SwaggerCustom\Lib\Path;

use SwaggerCustom\Lib\Annotation\SwagPath;
use SwaggerCustom\Lib\Configuration;
use SwaggerCustom\Lib\OpenApi\Path;
use SwaggerCustom\Lib\Route\RouteDecorator;
use SwaggerCustom\Lib\Utility\AnnotationUtility;

/**
 * Class PathFromRouteFactory
 *
 * @package SwaggerCustom\Lib\Path
 */
class PathFromRouteFactory
{
    /**
     * @var \SwaggerCustom\Lib\Route\RouteDecorator
     */
    private $route;

    /**
     * @var \SwaggerCustom\Lib\Configuration
     */
    private $config;

    /**
     * @param \SwaggerCustom\Lib\Route\RouteDecorator $route RouteDecorator
     * @param \SwaggerCustom\Lib\Configuration $config Configuration
     */
    public function __construct(RouteDecorator $route, Configuration $config)
    {
        $this->config = $config;
        $this->route = $route;
    }

    /**
     * Creates an instance of Path if possible, otherwise returns null
     *
     * @return \SwaggerCustom\Lib\OpenApi\Path|null
     */
    public function create(): ?Path
    {
        if (empty($this->route->getMethods())) {
            return null;
        }

        $fqn = $this->route->getControllerFqn();

        if (is_null($fqn)) {
            return null;
        }

        $path = (new Path())->setResource($this->route->templateToOpenApiPath());

        $swagPath = $this->getSwagPathAnnotation($fqn);

        if (is_null($swagPath)) {
            return $path;
        }

        if ($swagPath->isVisible === false) {
            return null;
        }

        return $path
            ->setRef($swagPath->ref ?? null)
            ->setDescription($swagPath->description ?? null)
            ->setSummary($swagPath->summary ?? null);
    }

    /**
     * Returns SwagPath if the controller has the annotation, otherwise null
     *
     * @param string $fqns Full qualified namespace of the Controller
     * @return \SwaggerCustom\Lib\Annotation\SwagPath|null
     */
    private function getSwagPathAnnotation(string $fqns): ?SwagPath
    {
        $annotations = AnnotationUtility::getClassAnnotationsFromFqns($fqns);

        $results = array_filter($annotations, function ($annotation) {
            return $annotation instanceof SwagPath;
        });

        if (empty($results)) {
            return null;
        }

        return reset($results);
    }
}
