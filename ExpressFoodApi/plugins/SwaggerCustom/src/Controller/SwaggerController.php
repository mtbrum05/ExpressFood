<?php
declare(strict_types=1);

namespace SwaggerCustom\Controller;

class SwaggerController extends AppController
{
    /**
     * @var \SwaggerCustom\Controller\Component\SwaggerUiComponent
     */
    public $SwaggerUi;

    /**
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->loadComponent('SwaggerCustom.SwaggerUi');
    }

    /**
     * Controller action for displaying built-in Swagger UI
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $config = $this->SwaggerUi->getSwaggerCustomConfiguration();
        $title = $config->getTitleFromYml();
        $url = $config->getWebPath();
        $this->set(compact('title', 'url'));
        $doctype = $this->SwaggerUi->getDocType($this->request);
        $this->viewBuilder()->setLayout($config->getLayout($doctype));

        return $this->render($config->getView($doctype));
    }
}
