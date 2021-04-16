<?php
declare(strict_types=1);

namespace SwaggerCustom\Lib\Operation;

use SwaggerCustom\Lib\Annotation\SwagHeader;
use SwaggerCustom\Lib\Factory\ParameterFromAnnotationFactory;
use SwaggerCustom\Lib\OpenApi\Operation;

/**
 * Class OperationHeader
 *
 * @package SwaggerCustom\Lib\Operation
 */
class OperationHeader
{
    /**
     * @param \SwaggerCustom\Lib\OpenApi\Operation $operation Operation
     * @param array $annotations Array of annotation objects
     * @return \SwaggerCustom\Lib\OpenApi\Operation
     */
    public function getOperationWithHeaders(Operation $operation, array $annotations): Operation
    {
        $swagHeaders = array_filter($annotations, function ($annotation) {
            return $annotation instanceof SwagHeader;
        });

        $factory = new ParameterFromAnnotationFactory();
        foreach ($swagHeaders as $annotation) {
            $operation->pushParameter($factory->create($annotation));
        }

        return $operation;
    }
}
