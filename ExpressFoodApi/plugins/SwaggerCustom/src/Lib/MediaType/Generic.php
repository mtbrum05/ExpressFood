<?php
declare(strict_types=1);

namespace SwaggerCustom\Lib\MediaType;

use SwaggerCustom\Lib\OpenApi\Schema;
use SwaggerCustom\Lib\OpenApi\SchemaProperty;
use SwaggerCustom\Lib\Swagger;

class Generic
{
    use GenericTrait;

    /**
     * @var \SwaggerCustom\Lib\OpenApi\Schema
     */
    private $schema;

    /**
     * @var \SwaggerCustom\Lib\Swagger
     */
    private $swagger;

    /**
     * @param \SwaggerCustom\Lib\OpenApi\Schema $schema instance of Schema
     * @param \SwaggerCustom\Lib\Swagger $swagger instance of Swagger
     */
    public function __construct(Schema $schema, Swagger $swagger)
    {
        $this->schema = $schema;
        $this->swagger = $swagger;
    }

    /**
     * Returns a generic schema
     *
     * @param string $action controller action (e.g. add, index, view, edit, delete)
     * @return \SwaggerCustom\Lib\OpenApi\Schema
     */
    public function buildSchema(string $action): Schema
    {
        if ($action == 'index') {
            return $this->collection();
        }

        return $this->item();
    }

    /**
     * @return \SwaggerCustom\Lib\OpenApi\Schema
     */
    private function collection(): Schema
    {
        $openapi = $this->swagger->getArray();

        if (!isset($openapi['x-swagger-bake']['components']['schemas']['Generic-Collection'])) {
            return (new Schema())
                ->setType('array')
                ->setItems(['$ref' => $this->schema->getReadSchemaRef()]);
        }

        return (new Schema())
            ->setAllOf([
                ['$ref' => '#/x-swagger-bake/components/schemas/Generic-Collection'],
            ])
            ->setProperties([
                (new SchemaProperty())
                    ->setName($this->whichData($openapi))
                    ->setType('array')
                    ->setItems([
                        'type' => 'object',
                        'allOf' => [
                            ['$ref' => $this->schema->getReadSchemaRef()],
                        ],
                    ]),
            ]);
    }

    /**
     * @return \SwaggerCustom\Lib\OpenApi\Schema
     */
    private function item(): Schema
    {
        return (new Schema())->setRefEntity($this->schema->getReadSchemaRef());
    }
}
