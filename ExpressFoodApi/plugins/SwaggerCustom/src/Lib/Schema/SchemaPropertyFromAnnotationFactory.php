<?php
declare(strict_types=1);

namespace SwaggerCustom\Lib\Schema;

use SwaggerCustom\Lib\Annotation\AbstractSchemaProperty;
use SwaggerCustom\Lib\OpenApi\SchemaProperty;

/**
 * Class SchemaPropertyFromAnnotationFactory
 *
 * @package SwaggerCustom\Lib\Schema
 */
class SchemaPropertyFromAnnotationFactory
{
    /**
     * Creates an instance of SchemaProperty from SwagEntityAttribute annotation
     *
     * @param \SwaggerCustom\Lib\Annotation\AbstractSchemaProperty $attribute Annotation extending AbstractSchemaProperty
     * @return \SwaggerCustom\Lib\OpenApi\SchemaProperty
     */
    public function create(AbstractSchemaProperty $attribute): SchemaProperty
    {
        $schemaProperty = (new SchemaProperty())
            ->setName($attribute->name)
            ->setDescription($attribute->description ?? '')
            ->setType($attribute->type)
            ->setFormat($attribute->format ?? '')
            ->setReadOnly($attribute->readOnly ?? false)
            ->setWriteOnly($attribute->writeOnly ?? false)
            ->setRequired($attribute->required ?? false)
            ->setEnum($attribute->enum ?? []);

        $properties = [
            'maxLength',
            'minLength',
            'pattern',
            'maxItems',
            'minItems',
            'uniqueItems',
            'maxProperties',
            'exclusiveMaximum',
            'exclusiveMinimum',
            'uniqueItems',
            'maxProperties',
            'minProperties',
            'example',
        ];

        foreach ($properties as $property) {
            if (is_null($attribute->{$property})) {
                continue;
            }
            $setterMethod = 'set' . ucfirst($property);
            $schemaProperty->{$setterMethod}($attribute->{$property});
        }

        return $schemaProperty;
    }
}
