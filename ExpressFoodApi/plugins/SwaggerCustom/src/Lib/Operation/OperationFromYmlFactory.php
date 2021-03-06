<?php
declare(strict_types=1);

namespace SwaggerCustom\Lib\Operation;

use SwaggerCustom\Lib\OpenApi\Operation;
use SwaggerCustom\Lib\OpenApi\OperationExternalDoc;
use SwaggerCustom\Lib\OpenApi\PathSecurity;

/**
 * Class OperationFromYmlFactory
 *
 * @package SwaggerCustom\Lib\Operation
 */
class OperationFromYmlFactory
{
    /**
     * Create Operation from YML
     *
     * @param string $httpMethod Http method i.e. PUT, POST, PATCH, GET, or DELETE
     * @param array $yaml OpenApi Operation YAML as an array
     * @return \SwaggerCustom\Lib\OpenApi\Operation
     */
    public function create(string $httpMethod, array $yaml): Operation
    {
        $operation = (new Operation())
            ->setHttpMethod($httpMethod)
            ->setTags($yaml['tags'] ?? [])
            ->setOperationId($yaml['operationId'] ?? '')
            ->setDeprecated($yaml['deprecated'] ?? false);

        if (isset($yaml['externalDocs']['url'])) {
            $operation->setExternalDocs(
                (new OperationExternalDoc())
                    ->setDescription(
                        $yaml['externalDocs']['description'] ?? ''
                    )
                    ->setUrl($yaml['externalDocs']['url'])
            );
        }

        if (isset($yaml['security']) && is_array($yaml['security'])) {
            foreach ($yaml['security'] as $key => $scopes) {
                $operation->pushSecurity((new PathSecurity())->setName($key)->setScopes($scopes));
            }
        }

        return $operation;
    }
}
