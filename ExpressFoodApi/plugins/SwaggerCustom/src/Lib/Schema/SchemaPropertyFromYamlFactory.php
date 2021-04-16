<?php
declare(strict_types=1);

namespace SwaggerCustom\Lib\Schema;

use SwaggerCustom\Lib\OpenApi\SchemaProperty;

/**
 * Class SchemaPropertyFromYamlFactory
 *
 * @package SwaggerCustom\Lib\Schema
 */
class SchemaPropertyFromYamlFactory
{
    /**
     * Creates an instance of SchemaProperty from YAML
     *
     * @param string $name Name of the Property (i.e. the column name from the database table)
     * @param array $yaml OpenApi YAML for the property as an array
     * @return \SwaggerCustom\Lib\OpenApi\SchemaProperty
     */
    public function create(string $name, array $yaml): SchemaProperty
    {
        $schemaProperty = (new SchemaProperty())
            ->setName($name)
            ->setDescription($yaml['description'] ?? '')
            ->setType($yaml['type'] ?? '')
            ->setReadOnly($yaml['readonly'] ?? false)
            ->setWriteOnly($yaml['writeOnly'] ?? false)
            ->setRequired($yaml['required'] ?? false)
            ->setEnum($yaml['enum'] ?? [])
            ->setExample($yaml['example'] ?? '')
            ->setItems($yaml['items'] ?? [])
            ->setFormat($yaml['format'] ?? '');

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
        ];

        foreach ($properties as $property) {
            if (!isset($yaml[$property])) {
                continue;
            }
            $setterMethod = 'set' . ucfirst($property);
            $schemaProperty->{$setterMethod}($yaml[$property]);
        }

        return $schemaProperty;
    }
}
