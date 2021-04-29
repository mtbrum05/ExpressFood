<?php
namespace App\Controller\Component;

use Exception;
use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

/**
 * ErrorHandler component
 */
class ErrorHandlerComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    protected $_controller = null;
    /**
     * @param array $config The config data.
     * @return void
     * @deprecated 3.4.0 Unused. To be removed in 4.0.0
     */
    public function initialize(Array $options): void
    {   
        if (empty($options['controller'])) throw new Exception("Controller not set");
        $this->_controller = $options['controller'];

    }

    public function errorHandler($e, $error_code = 500) {
        $dados = [
            "data" => null,
            "message" => $e->getMessage()
        ];
        return $this->_controller->getResponse()
                    ->withStatus($error_code)
                    ->withType('application/json')
                    ->withStringBody(json_encode($dados));
    } 

    public function errorHandlerConstraintViolation($e, $error_code = 400) {

        $message = "Deletar este registro pode gerar dados orfãos, exclusão não permitida!";

        $dados = [
            "data" => null,
            "message" => $message
        ];
        return $this->_controller->response
                    ->withStatus($error_code)
                    ->withType('application/json')
                    ->withStringBody(json_encode($dados));
    }   
    
}