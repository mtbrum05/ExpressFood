<?php
declare(strict_types=1);

namespace SwaggerCustomTest\App\Controller;

use SwaggerCustom\Lib\Annotation as Swag;
use SwaggerCustom\Lib\Extension\CakeSearch\Annotation\SwagSearch;

class OperationsController extends AppController
{
    /**
     * @Swag\SwagOperation(isVisible=false)
     */
    public function isVisible()
    {

    }

    /**
     * @Swag\SwagOperation(tagNames={"These","Tags","Are","Might"})
     */
    public function tagNames()
    {

    }
}