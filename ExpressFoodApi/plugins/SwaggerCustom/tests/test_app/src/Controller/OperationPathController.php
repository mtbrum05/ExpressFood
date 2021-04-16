<?php
declare(strict_types=1);

namespace SwaggerCustomTest\App\Controller;

use SwaggerCustom\Lib\Annotation as Swag;
use SwaggerCustom\Lib\Extension\CakeSearch\Annotation\SwagSearch;

class OperationPathController extends AppController
{
    /**
     * @Swag\SwagPathParameter(name="id", type="integer", format="int64", description="ID")
     */
    public function pathParameter($id = null)
    {

    }
}