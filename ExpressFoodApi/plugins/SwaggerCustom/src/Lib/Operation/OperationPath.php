<?php
declare(strict_types=1);

namespace SwaggerCustom\Lib\Operation;

use SwaggerCustom\Lib\Annotation\SwagPathParameter;
use SwaggerCustom\Lib\OpenApi\Operation;
use SwaggerCustom\Lib\OpenApi\Parameter;
use SwaggerCustom\Lib\OpenApi\Schema;
use SwaggerCustom\Lib\Route\RouteDecorator;

/**
 * Class OperationPath
 *
 * @package SwaggerCustom\Lib\Operation
 */
class OperationPath
{
    /**
     * @var \SwaggerCustom\Lib\OpenApi\Operation
     */
    private $operation;

    /**
     * @var \SwaggerCustom\Lib\Route\RouteDecorator
     */
    private $route;

    /**
     * @var array
     */
    private $annotations;

    /**
     * @var \SwaggerCustom\Lib\OpenApi\Schema|null
     */
    private $schema;

    /**
     * @param \SwaggerCustom\Lib\OpenApi\Operation $operation instance of Operation
     * @param \SwaggerCustom\Lib\Route\RouteDecorator $route instance of RouteDecorator
     * @param array $annotations array of annotation objects or empty array
     * @param \SwaggerCustom\Lib\OpenApi\Schema|null $schema instance of Schema or null
     */
    public function __construct(
        Operation $operation,
        RouteDecorator $route,
        array $annotations = [],
        ?Schema $schema = null
    ) {
        $this->operation = $operation;
        $this->route = $route;
        $this->annotations = $annotations;
        $this->schema = $schema;
    }

    /**
     * Adds Path Parameters to the Operation
     *
     * @return \SwaggerCustom\Lib\OpenApi\Operation
     */
    public function getOperationWithPathParameters(): Operation
    {
        $this->assignPathParametersFromRoute();
        $this->updatePathParametersUsingAnnotations();

        return $this->operation;
    }

    /**
     * Adds Path Parameters from existing routes
     *
     * @return void
     */
    private function assignPathParametersFromRoute(): void
    {
        $pieces = explode('/', $this->route->getTemplate());
        $results = array_filter($pieces, function ($piece) {
            return substr($piece, 0, 1) == ':' ? true : null;
        });

        $properties = $this->schema instanceof Schema ? $this->schema->getProperties() : [];

        foreach ($results as $result) {
            $name = strtolower($result);

            if (substr($name, 0, 1) == ':') {
                $name = substr($name, 1);
            }

            if (isset($properties[$name])) {
                $type = $properties[$name]->getType();
                $format = $properties[$name]->getFormat();
                $description = $properties[$name]->getDescription();
            }

            $this->operation->pushParameter(
                (new Parameter())
                    ->setName($name)
                    ->setDescription($description ?? '')
                    ->setRequired(true)
                    ->setIn('path')
                    ->setSchema(
                        (new Schema())->setType($type ?? 'string')->setFormat($format ?? '')
                    )
            );
        }
    }

    /**
     * Updates Path Parameters using values from SwagPathParameter annotation. The path parameter must already exist
     * having been added from routes. This will not add new parameters, only update existing ones.
     *
     * @return void
     */
    private function updatePathParametersUsingAnnotations(): void
    {
        /**
         * @var \SwaggerCustom\Lib\Annotation\SwagPathParameter[] $swagPathParameters
         */
        $swagPathParameters = array_filter($this->annotations, function ($annotation) {
            return $annotation instanceof SwagPathParameter;
        });

        if (empty($swagPathParameters)) {
            return;
        }

        $parameters = $this->operation->getParameters();

        foreach ($swagPathParameters as $pathParameter) {
            $params = array_filter($this->operation->getParameters(), function ($parameter) use ($pathParameter) {
                return $parameter->getIn() == 'path' && $pathParameter->name == $parameter->getName();
            });

            $keys = array_keys($params);
            $index = reset($keys);

            $parameters[$index]
                ->setName($pathParameter->name)
                ->setExample($pathParameter->example)
                ->setDescription($pathParameter->description)
                ->setAllowReserved($pathParameter->allowReserved)
                ->setSchema(
                    (new Schema())->setType($pathParameter->type)->setFormat($pathParameter->format)
                );
        }

        $this->operation->setParameters($parameters);
    }
}
