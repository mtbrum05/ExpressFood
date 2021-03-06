<?php
declare(strict_types=1);

namespace SwaggerCustom\Lib\Operation;

use Doctrine\Common\Annotations\AnnotationReader;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use ReflectionClass;
use ReflectionProperty;
use SwaggerCustom\Lib\Annotation\SwagDtoForm;
use SwaggerCustom\Lib\Annotation\SwagDtoQuery;
use SwaggerCustom\Lib\Annotation\SwagDtoRequestBody;
use SwaggerCustom\Lib\Factory\ParameterFromAnnotationFactory;
use SwaggerCustom\Lib\OpenApi\Parameter;
use SwaggerCustom\Lib\OpenApi\Schema;
use SwaggerCustom\Lib\OpenApi\SchemaProperty;
use SwaggerCustom\Lib\Schema\SchemaPropertyFromAnnotationFactory;
use SwaggerCustom\Lib\Utility\DocBlockUtility;

class DtoParser
{
    /**
     * @var string
     */
    private $fqns;

    /**
     * @var object
     */
    private $instance;

    /**
     * @var \Doctrine\Common\Annotations\AnnotationReader
     */
    private $annotationReader;

    /**
     * @param string $fqns Fully qualified namespace of the DTO
     * @throws \ReflectionException
     */
    public function __construct(string $fqns)
    {
        $this->fqns = $fqns;
        $this->instance = (new ReflectionClass($this->fqns))->newInstanceWithoutConstructor();
        $this->annotationReader = new AnnotationReader();
    }

    /**
     * Returns an array of Parameter instances for use in Query Parameters
     *
     * @return \SwaggerCustom\Lib\OpenApi\Parameter[]
     * @throws \ReflectionException
     */
    public function getParameters(): array
    {
        $parameters = [];

        $properties = $this->getClassProperties();

        $factory = new ParameterFromAnnotationFactory();

        foreach ($properties as $reflectionProperty) {
            $swagDtoQuery = $this->getSwagDtoProperty($reflectionProperty);
            if ($swagDtoQuery instanceof SwagDtoQuery) {
                $parameters[] = $factory->create($swagDtoQuery)->setIn('query');
                continue;
            }

            $docBlock = DocBlockUtility::getPropertyDocBlock($reflectionProperty);
            $var = $this->getDocBlockVarTag($docBlock);
            $dataType = $var !== null ? DocBlockUtility::getDocBlockConvertedVar($var) : null;

            $parameters[] = (new Parameter())
                ->setName($reflectionProperty->getName())
                ->setIn('query')
                ->setRequired(!empty($docBlock->getTagsByName('required')))
                ->setDescription($docBlock->getSummary())
                ->setSchema((new Schema())->setType($dataType ?? 'string'));
        }

        return $parameters;
    }

    /**
     * Returns an array of SchemaProperty instances for use in Body Requests
     *
     * @return \SwaggerCustom\Lib\OpenApi\SchemaProperty[]
     * @throws \ReflectionException
     */
    public function getSchemaProperties(): array
    {
        $schemaProperties = [];

        $properties = $this->getClassProperties();

        $factory = new SchemaPropertyFromAnnotationFactory();

        foreach ($properties as $name => $reflectionProperty) {
            $dto = $this->getSwagDtoProperty($reflectionProperty);
            if ($dto instanceof SwagDtoForm || $dto instanceof SwagDtoRequestBody) {
                $schemaProperties[] = $factory->create($dto);
                continue;
            }

            $docBlock = DocBlockUtility::getPropertyDocBlock($reflectionProperty);
            $var = $this->getDocBlockVarTag($docBlock);
            $dataType = $var !== null ? DocBlockUtility::getDocBlockConvertedVar($var) : null;

            $schemaProperties[] = (new SchemaProperty())
                ->setDescription($docBlock->getSummary())
                ->setName($name)
                ->setType($dataType)
                ->setRequired(!empty($docBlock->getTagsByName('required')));
        }

        return $schemaProperties;
    }

    /**
     * Gets an instance of SwagDtoProperty, null otherwise
     *
     * @param \ReflectionProperty $reflectionProperty ReflectionProperty
     * @return mixed
     */
    private function getSwagDtoProperty(ReflectionProperty $reflectionProperty)
    {
        try {
            $annotation = $this->annotationReader->getPropertyAnnotation(
                $reflectionProperty,
                SwagDtoQuery::class
            );
            if ($annotation instanceof SwagDtoQuery && !empty($annotation->name)) {
                return $annotation;
            }

            $annotation = $this->annotationReader->getPropertyAnnotation(
                $reflectionProperty,
                SwagDtoForm::class
            );
            if ($annotation instanceof SwagDtoForm && !empty($annotation->name)) {
                return $annotation;
            }

            $annotation = $this->annotationReader->getPropertyAnnotation(
                $reflectionProperty,
                SwagDtoRequestBody::class
            );
            if ($annotation instanceof SwagDtoRequestBody && !empty($annotation->name)) {
                return $annotation;
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }

    /**
     * Returns an array of class properties
     *
     * @return array
     */
    private function getClassProperties(): array
    {
        $properties = DocBlockUtility::getProperties($this->instance);

        if (empty($properties)) {
            return [];
        }

        return array_filter($properties, function ($property) {
            if (!isset($property->class) || $property->class != get_class($this->instance)) {
                return null;
            }

            return true;
        });
    }

    /**
     * Returns `@var` tag as Var_ instance or null
     *
     * @param \phpDocumentor\Reflection\DocBlock $docBlock DocBlock
     * @return \phpDocumentor\Reflection\DocBlock\Tags\Var_|null
     */
    private function getDocBlockVarTag(DocBlock $docBlock): ?Var_
    {
        $vars = array_filter($docBlock->getTagsByName('var'), function ($var) {
            return $var instanceof Var_;
        });

        if (empty($vars)) {
            return null;
        }

        return reset($vars);
    }
}
