<?php
declare(strict_types=1);

namespace SwaggerCustom\Lib\Factory;

use SwaggerCustom\Lib\Annotation\AbstractParameter;
use SwaggerCustom\Lib\Annotation\SwagHeader;
use SwaggerCustom\Lib\Annotation\SwagQuery;
use SwaggerCustom\Lib\OpenApi\Parameter;
use SwaggerCustom\Lib\OpenApi\Schema;

class ParameterFromAnnotationFactory
{
    /**
     * Creates an instance of Parameter from an AbstractParameter annotation
     *
     * @param \SwaggerCustom\Lib\Annotation\AbstractParameter $annotation Class extending AbstractParameter
     * @return \SwaggerCustom\Lib\OpenApi\Parameter
     */
    public function create(AbstractParameter $annotation): Parameter
    {
        $parameter = (new Parameter())
            ->setRef($annotation->ref ?? '')
            ->setName($annotation->name ?? '')
            ->setDescription($annotation->description)
            ->setRequired($annotation->required)
            ->setDeprecated($annotation->deprecated)
            ->setStyle($annotation->style)
            ->setExplode($annotation->explode)
            ->setExample($annotation->example)
            ->setSchema(
                (new Schema())
                    ->setType($annotation->type)
                    ->setEnum($annotation->enum)
                    ->setFormat($annotation->format)
            );

        if ($annotation instanceof SwagQuery) {
            $parameter
                ->setIn('query')
                ->setAllowReserved($annotation->allowReserved)
                ->setAllowEmptyValue($annotation->allowEmptyValue);
        } elseif ($annotation instanceof SwagHeader) {
            $parameter->setIn('header');
        }

        return $parameter;
    }
}
