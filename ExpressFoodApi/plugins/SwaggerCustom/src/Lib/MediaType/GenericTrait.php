<?php
declare(strict_types=1);

namespace SwaggerCustom\Lib\MediaType;

use SwaggerCustom\Lib\OpenApi\Schema;

trait GenericTrait
{
    /**
     * Determines the name of the element that contains the collections items
     *
     * @param array $openapi openapi array
     * @return string
     */
    private function whichData(array $openapi): string
    {
        if (!isset($openapi['x-swagger-bake']['components']['schemas']['Generic-Collection'])) {
            return 'data';
        }

        if ($openapi['x-swagger-bake']['components']['schemas']['Generic-Collection'] instanceof Schema) {
            /** @var \SwaggerCustom\Lib\OpenApi\Schema $schema */
            $schema = $openapi['x-swagger-bake']['components']['schemas']['Generic-Collection'];
            $array = $schema->toArray();

            return $array['x-data-element'] ?? 'data';
        }

        if (is_array($openapi['x-swagger-bake']['components']['schemas']['Generic-Collection'])) {
            return $openapi['x-swagger-bake']['components']['schemas']['Generic-Collection']['x-data-element'];
        }

        return 'data';
    }
}
