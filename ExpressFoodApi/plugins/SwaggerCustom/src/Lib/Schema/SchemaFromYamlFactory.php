<?php
declare(strict_types=1);

namespace SwaggerCustom\Lib\Schema;

use SwaggerCustom\Lib\OpenApi\Schema;
use SwaggerCustom\Lib\OpenApi\Xml;

/**
 * Class SchemaFromYamlFactory
 *
 * @package SwaggerCustom\Lib\Schema
 */
class SchemaFromYamlFactory
{
    /**
     * Create an instance of Schema from YAML
     *
     * @param string $name Name of the Schema (i.e. cake entity name)
     * @param array $yml OpenApi YAML as an array
     * @return \SwaggerCustom\Lib\OpenApi\Schema
     */
    public function create(string $name, array $yml): Schema
    {
        $schema = (new Schema())
            ->setName($name)
            ->setTitle($yml['title'] ?? '')
            ->setType($yml['type'] ?? '')
            ->setDescription($yml['description'] ?? '')
            ->setItems($yml['items'] ?? [])
            ->setAllOf($yml['allOf'] ?? [])
            ->setAnyOf($yml['anyOf'] ?? [])
            ->setOneOf($yml['oneOf'] ?? [])
            ->setNot($yml['oneOf'] ?? []);

        if (isset($yml['xml'])) {
            $schema->setXml(
                (new Xml())
                    ->setName($yml['xml']['name'])
                    ->setAttribute($yml['xml']['attribute'] ?? null)
                    ->setNamespace($yml['xml']['namespace'] ?? null)
                    ->setPrefix($yml['xml']['prefix'] ?? null)
                    ->setWrapped($yml['xml']['wrapped'] ?? null)
            );
        }

        $factory = new SchemaPropertyFromYamlFactory();
        $yml['properties'] = $yml['properties'] ?? [];

        foreach ($yml['properties'] as $propertyName => $propertyVar) {
            $schema->pushProperty(
                $factory->create($propertyName, $propertyVar)
            );
        }

        return $schema;
    }
}
