<?php
declare(strict_types=1);

namespace SwaggerCustom\Lib\Operation;

use Cake\Controller\Controller;
use SwaggerCustom\Lib\Annotation\SwagDto;
use SwaggerCustom\Lib\Annotation\SwagPaginator;
use SwaggerCustom\Lib\Annotation\SwagQuery;
use SwaggerCustom\Lib\Exception\SwaggerCustomRunTimeException;
use SwaggerCustom\Lib\Factory\ParameterFromAnnotationFactory;
use SwaggerCustom\Lib\OpenApi\Operation;
use SwaggerCustom\Lib\OpenApi\Parameter;
use SwaggerCustom\Lib\OpenApi\Schema;

/**
 * Class OperationQueryParameter
 *
 * @package SwaggerCustom\Lib\Operation
 */
class OperationQueryParameter
{
    /**
     * @var \SwaggerCustom\Lib\OpenApi\Operation
     */
    private $operation;

    /**
     * Array of annotations
     *
     * @var array
     */
    private $annotations;

    /**
     * @var \Cake\Controller\Controller
     */
    private $controller;

    /**
     * @var \SwaggerCustom\Lib\OpenApi\Schema
     */
    private $schema;

    /**
     * Adds query parameters to the Operation
     *
     * @param \SwaggerCustom\Lib\OpenApi\Operation $operation instance of the Operation
     * @param array $annotations an array of annotation objects
     * @param \Cake\Controller\Controller $controller instance of the Controller
     * @param \SwaggerCustom\Lib\OpenApi\Schema $schema instance of Schema
     * @return \SwaggerCustom\Lib\OpenApi\Operation
     */
    public function __construct(
        Operation $operation,
        array $annotations,
        Controller $controller,
        ?Schema $schema = null
    ) {
        $this->operation = $operation;
        $this->annotations = $annotations;
        $this->controller = $controller;
        $this->schema = $schema;
    }

    /**
     * Adds query parameters to the Operation
     *
     * @return \SwaggerCustom\Lib\OpenApi\Operation
     */
    public function getOperationWithQueryParameters(): Operation
    {
        if ($this->operation->getHttpMethod() != 'GET') {
            return $this->operation;
        }

        $this->definePagination();
        $this->defineFromAnnotations();

        try {
            $this->defineDataTransferObjectFromAnnotations();
        } catch (\ReflectionException $e) {
            throw new SwaggerCustomRunTimeException('ReflectionException: ' . $e->getMessage());
        }

        return $this->operation;
    }

    /**
     * Adds CakePHP Paginator query parameters to the Operation
     *
     * @return void
     */
    private function definePagination()
    {
        $results = array_filter($this->annotations, function ($annotation) {
            return $annotation instanceof SwagPaginator;
        });

        if (empty($results)) {
            return;
        }

        /** @var \SwaggerCustom\Lib\Annotation\SwagPaginator $swagPaginator */
        $swagPaginator = reset($results);

        $this->operation->pushRefParameter('#/x-swagger-bake/components/parameters/paginatorPage');
        $this->operation->pushRefParameter('#/x-swagger-bake/components/parameters/paginatorLimit');
        $this->pushSortParameter($swagPaginator);
        $this->operation->pushRefParameter('#/x-swagger-bake/components/parameters/paginatorDirection');
    }

    /**
     * Pushes the sort parameter into the Operation based on SwagPaginator attributes
     *
     * @param \SwaggerCustom\Lib\Annotation\SwagPaginator $swagPaginator SwagPaginator
     * @return void
     */
    private function pushSortParameter(SwagPaginator $swagPaginator): void
    {
        if ($swagPaginator->useSortTextInput === true) {
            $this->operation->pushRefParameter('#/x-swagger-bake/components/parameters/paginatorSort');

            return;
        }

        $parameter = (new Parameter())
            ->setName('sort')
            ->setIn('query')
            ->setSchema((new Schema())->setType('string'))
            ->setAllowEmptyValue(false)
            ->setDeprecated(false)
            ->setRequired(false);

        if (!empty($swagPaginator->sortEnum)) {
            $schema = $parameter->getSchema()->setEnum($swagPaginator->sortEnum);
            $this->operation->pushParameter($parameter->setSchema($schema));

            return;
        }

        if (isset($this->controller->paginate['sortableFields'])) {
            $schema = $parameter->getSchema()->setEnum($this->controller->paginate['sortableFields']);
            $this->operation->pushParameter($parameter->setSchema($schema));

            return;
        }

        if ($this->schema != null && is_array($this->schema->getProperties())) {
            $enumList = [];
            foreach ($this->schema->getProperties() as $property) {
                $enumList[] = $property->getName();
            }
            $schema = $parameter->getSchema()->setEnum($enumList);
            $this->operation->pushParameter($parameter->setSchema($schema));

            return;
        }

        $this->operation->pushRefParameter('#/x-swagger-bake/components/parameters/paginatorSort');
    }

    /**
     * Adds query parameters from SwagPaginator to the Operation
     *
     * @return void
     */
    private function defineFromAnnotations(): void
    {
        $swagQueries = array_filter($this->annotations, function ($annotation) {
            return $annotation instanceof SwagQuery;
        });

        $factory = new ParameterFromAnnotationFactory();
        foreach ($swagQueries as $annotation) {
            $this->operation->pushParameter($factory->create($annotation));
        }
    }

    /**
     * Adds query parameters from SwagDto to the Operation
     *
     * @return void
     * @throws \ReflectionException
     */
    private function defineDataTransferObjectFromAnnotations(): void
    {
        $swagDtos = array_filter($this->annotations, function ($annotation) {
            return $annotation instanceof SwagDto;
        });

        if (empty($swagDtos)) {
            return;
        }

        $dto = reset($swagDtos);
        $fqns = $dto->class;

        if (!class_exists($fqns)) {
            return;
        }

        $parameters = (new DtoParser($fqns))->getParameters();
        foreach ($parameters as $parameter) {
            $this->operation->pushParameter($parameter);
        }
    }
}
