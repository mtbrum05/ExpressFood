<?php
declare(strict_types=1);

namespace SwaggerCustom\Lib\Operation;

use phpDocumentor\Reflection\DocBlock;
use SwaggerCustom\Lib\OpenApi\Operation;
use SwaggerCustom\Lib\OpenApi\OperationExternalDoc;

/**
 * Class OperationDocBlock
 *
 * @package SwaggerCustom\Lib\Operation
 */
class OperationDocBlock
{
    /**
     * Adds PHP Doc Block tags and meta data to the Operation
     *
     * @param \SwaggerCustom\Lib\OpenApi\Operation $operation Operation
     * @param \phpDocumentor\Reflection\DocBlock $doc DocBlock
     * @return \SwaggerCustom\Lib\OpenApi\Operation
     */
    public function getOperationWithDocBlock(Operation $operation, DocBlock $doc): Operation
    {
        if ($doc->hasTag('deprecated')) {
            $operation->setDeprecated(true);
        }

        if (!$doc->hasTag('see')) {
            return $operation;
        }

        $tags = $doc->getTagsByName('see');
        $seeTag = reset($tags);
        $str = $seeTag->__toString();
        $pieces = explode(' ', $str);

        if (!filter_var($pieces[0], FILTER_VALIDATE_URL)) {
            return $operation;
        }

        $externalDoc = new OperationExternalDoc();
        $externalDoc->setUrl($pieces[0]);

        array_shift($pieces);

        if (!empty($pieces)) {
            $externalDoc->setDescription(implode(' ', $pieces));
        }

        return $operation->setExternalDocs($externalDoc);
    }
}
