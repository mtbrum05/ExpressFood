<?php
declare(strict_types=1);

namespace SwaggerCustom\Lib\MediaType;

use SwaggerCustom\Lib\OpenApi\Schema;
use SwaggerCustom\Lib\OpenApi\SchemaProperty;

class JsonLd
{
    /**
     * @var string
     */
    public const JSONLD_ITEM = '#/x-swagger-bake/components/schemas/JsonLd-Item';

    /**
     * @var string
     */
    public const JSONLD_COLLECTION = '#/x-swagger-bake/components/schemas/JsonLd-Collection';

    /**
     * @var \SwaggerCustom\Lib\OpenApi\Schema
     */
    private $schema;

    /**
     * @param \SwaggerCustom\Lib\OpenApi\Schema $schema instance of Schema
     */
    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
    }

    /**
     * Returns JSON-LD schema
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
        return (new Schema())
            ->setAllOf([
                ['$ref' => self::JSONLD_COLLECTION],
            ])
            ->setProperties([
                (new SchemaProperty())
                    ->setName('member')
                    ->setType('array')
                    ->setItems([
                        'type' => 'object',
                        'allOf' => [
                            ['$ref' => self::JSONLD_ITEM],
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
        return (new Schema())
            ->setAllOf([
                ['$ref' => self::JSONLD_ITEM],
                ['$ref' => $this->schema->getReadSchemaRef()],
            ])
            ->setProperties([]);
    }
}
