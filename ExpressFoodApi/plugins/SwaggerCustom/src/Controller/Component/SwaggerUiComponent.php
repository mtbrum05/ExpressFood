<?php
declare(strict_types=1);

namespace SwaggerCustom\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Event\Event;
use Cake\Http\ServerRequest;
use SwaggerCustom\Lib\Configuration;
use SwaggerCustom\Lib\Factory\SwaggerFactory;

/**
 * Class SwaggerUiComponent
 *
 * @package SwaggerCustom\Controller\Component
 */
class SwaggerUiComponent extends Component
{
    public $components = ['Flash'];

    /**
     * @var \SwaggerCustom\Lib\Configuration
     */
    public $config;

    /**
     * @var \SwaggerCustom\Lib\Swagger
     */
    public $swagger;

    /**
     * SwaggerUiComponent constructor.
     *
     * @param \Cake\Controller\ComponentRegistry $registry ComponentRegistry
     * @param array $config configurations
     */
    public function __construct(ComponentRegistry $registry, array $config = [])
    {
        parent::__construct($registry, $config);
    }

    /**
     * @param \Cake\Event\Event $event Event
     * @return void
     */
    public function beforeFilter(Event $event): void
    {
        $this->config = new Configuration();
        $this->swagger = (new SwaggerFactory())->create();

        if ($this->config->isHotReload()) {
            $output = $this->config->getJson();
            $this->swagger->writeFile($output);
        }

        foreach ($this->swagger->getOperationsWithNoHttp20x() as $operation) {
            $errorMsg = 'Operation ' . $operation->getOperationId() . ' does not have a HTTP 20x response';
            if (!$this->_registry->has('Flash')) {
                triggerWarning($errorMsg);
                continue;
            }
            $this->_registry->getController()->Flash->error($errorMsg);
        }
    }

    /**
     * @return \SwaggerCustom\Lib\Configuration
     */
    public function getSwaggerCustomConfiguration(): Configuration
    {
        return $this->config;
    }

    /**
     * @param \Cake\Http\ServerRequest $request ServerRequest
     * @return string
     */
    public function getDocType(ServerRequest $request): string
    {
        $docType = 'swagger';
        if (!empty($request->getQuery('doctype'))) {
            $docType = h(strtolower($request->getQuery('doctype')));
        }

        return in_array(strtolower($docType), ['swagger','redoc']) ? $docType : 'swagger';
    }
}
