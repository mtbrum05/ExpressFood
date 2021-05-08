<?php
declare(strict_types=1);

namespace App\Controller;

use Exception;
use Cake\Utility\Security;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Exception\BadRequestException;

/**
 * Cliente Controller
 *
 * @method \App\Model\Entity\Cliente[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ClienteController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Paginador');
        $this->loadModel('Usuario');

    }

    public function index()
    {
        try {

            $this->paginate = array(
                'limit' => 10,
                'conditions' => array(),
            );

            $queryString = $this->getRequest()->getQueryParams();
            $authUser = $this->Auth->User();

            if($authUser['tipo_usuario'] != 2){
                if (isset($authUser['codigo_empresa'])){
                    $message = 'Registro disponivel apenas para Administradores';
                    throw new BadRequestException($message);
                }
                $this->paginate['conditions']['Cliente.codigo'] = $authUser['codigo_cliente'];
            }
            if(!empty($queryString['nome'])){
                $this->paginate['conditions']['Cliente.nome LIKE'] = '%'.$queryString['nome'].'%';          
            } 
            if(!empty($queryString['codigo_cliente'])){
                $this->paginate['conditions']['Cliente.codigo'] = $queryString['codigo_cliente'];          
            } 

            $cliente = $this->paginate($this->Cliente->find()
                                        ->contain('Usuario'));

            $this->set([
                'data' => [
                    'cliente' => $cliente,
                ],
            ]);
            $this->viewBuilder()->setOption('serialize', true);
        } catch (BadRequestException $e) {
            return $this->ErrorHandler->errorHandlerMessage($e, 400);
        } catch (NotFoundException $e) { 
            return $this->ErrorHandler->errorHandlerMessage($e, 404);
        } catch (Exception $e) {
            debug($e);die;
            return $this->ErrorHandler->errorHandler($e, 500);
        }
    }

    public function view($id = null)
    {
        try {
            $authUser = $this->Auth->User();

            $conditions = $this->Paginador->validadorTipoUsuarioCliente($authUser);

            $cliente = $this->Cliente->findByCodigo($id)
                                                   ->where($conditions)
                                                   ->contain('Usuario')
                                                   ->first();

            if(!$cliente){
                $dados = ['cliente' => ['_error' => 'Registro não encontrado.']];
                throw new NotFoundException(json_encode($dados));
            }

            $this->set([
                'data' => [
                    'cliente' => $cliente,
                ],
            ]);
            $this->viewBuilder()->setOption('serialize', true);

        } catch (NotFoundException $e) {
            return $this->ErrorHandler->errorHandler($e, 404);
        } catch (Exception $e) {
            return $this->ErrorHandler->errorHandler($e, 500);
        }
    }

    public function add()
    {
        try {
            
            $data = $this->request->getData();

            $retorno = $this->Cliente->adicionarCliente($data);

            $this->set([
                'data' => [
                    'message' => $retorno['message'],
                    'cliente' => $retorno['cliente'],
                    'usuario' => $retorno['usuario'],
                ],
            ]);
            $this->viewBuilder()->setOption('serialize', true);

        } catch (BadRequestException $e) { //400
            return $this->ErrorHandler->errorHandler($e, 400);
        } catch (Exception $e) {
            debug($e);die;
            return $this->ErrorHandler->errorHandler($e, 500);
        }
    }



    
}
