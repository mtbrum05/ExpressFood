<?php
declare(strict_types=1);

namespace SwaggerCustom\Lib\Operation;

use Cake\Controller\Controller;
use SwaggerCustom\Lib\Annotation\SwagSecurity;
use SwaggerCustom\Lib\OpenApi\Operation;
use SwaggerCustom\Lib\OpenApi\PathSecurity;
use SwaggerCustom\Lib\Route\RouteDecorator;
use SwaggerCustom\Lib\Swagger;

/**
 * Class OperationSecurity
 *
 * @package SwaggerCustom\Lib\Operation
 */
class OperationSecurity
{
    /**
     * @var \SwaggerCustom\Lib\OpenApi\Operation
     */
    private $operation;

    /**
     * @var array
     */
    private $annotations;

    /**
     * @var \SwaggerCustom\Lib\Route\RouteDecorator
     */
    private $route;

    /**
     * @var \Cake\Controller\Controller
     */
    private $controller;

    /**
     * @var \SwaggerCustom\Lib\Swagger
     */
    private $swagger;

    /**
     * @param \SwaggerCustom\Lib\OpenApi\Operation $operation Operation
     * @param array $annotations Array of annotation objects
     * @param \SwaggerCustom\Lib\Route\RouteDecorator $route RouteDecorator
     * @param \Cake\Controller\Controller $controller Controller
     * @param \SwaggerCustom\Lib\Swagger $swagger Swagger
     */
    public function __construct(
        Operation $operation,
        array $annotations,
        RouteDecorator $route,
        Controller $controller,
        Swagger $swagger
    ) {
        $this->operation = $operation;
        $this->annotations = $annotations;
        $this->route = $route;
        $this->controller = $controller;
        $this->swagger = $swagger;
    }

    /**
     * Gets an Operation instance after applying security
     *
     * @return \SwaggerCustom\Lib\OpenApi\Operation
     */
    public function getOperationWithSecurity(): Operation
    {
        $this->assignSwagSecurityAnnotations();
        $this->assignAuthenticationComponent();

        return $this->operation;
    }

    /**
     * Assigns @SwagSecurity annotations
     *
     * @return void
     */
    private function assignSwagSecurityAnnotations(): void
    {
        $swagSecurities = array_filter($this->annotations, function ($annotation) {
            return $annotation instanceof SwagSecurity;
        });

        foreach ($swagSecurities as $annotation) {
            $this->operation->pushSecurity(
                (new PathSecurity())
                    ->setName($annotation->name)
                    ->setScopes($annotation->scopes)
            );
        }
    }

    /**
     * Assign by AuthenticationComponent
     *
     * @return void
     */
    private function assignAuthenticationComponent(): void
    {
        if (!isset($this->controller->Authentication)) {
            return;
        }

        if (in_array($this->route->getAction(), $this->controller->Authentication->getUnauthenticatedActions())) {
            return;
        }

        $array = $this->swagger->getArray();
        if (!isset($array['components']['securitySchemes']) || count($array['components']['securitySchemes']) !== 1) {
            return;
        }

        $scheme = array_keys($array['components']['securitySchemes'])[0];

        $securities = $this->operation->getSecurity();
        if (array_key_exists($scheme, $securities)) {
            return;
        }

        $this->operation->pushSecurity(
            (new PathSecurity())
                ->setName($scheme)
                ->setScopes([])
        );
    }
}
