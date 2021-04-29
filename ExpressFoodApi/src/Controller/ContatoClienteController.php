<?php
declare(strict_types=1);

namespace App\Controller;

use Exception;
use Cake\Http\Exception\BadRequestException;
use League\Container\Exception\NotFoundException;

/**
 * ContatoCliente Controller
 *
 * @property \App\Model\Table\ContatoClienteTable $ContatoCliente
 * @method \App\Model\Entity\ContatoCliente[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ContatoClienteController extends AppController
{
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
                $this->paginate['conditions']['codigo_cliente'] = $authUser['codigo'];
            }

            if(!empty($queryString['descricao'])){
                $this->paginate['conditions']['descricao LIKE'] = '%'.$queryString['descricao'].'%';          
            } 
            if(!empty($queryString['codigo_cliente'])){
                $this->paginate['conditions']['codigo_cliente'] = $queryString['codigo_cliente'];          
            } 

            if(isset($queryString['ativo']) && is_numeric($queryString['ativo'])){
                if(in_array($queryString['ativo'],array(0,1))){
                    $this->paginate['conditions']['ativo'] = $queryString['ativo'];          
                }else{
                    $message = 'Ativo deve receber (1)true ou (0)false.';
                    throw new BadRequestException($message);
                }
            } else if(isset($queryString['ativo']) && !is_numeric($queryString['ativo'])){
                $message = 'Ativo deve receber (1)true ou (0)false.';
                throw new BadRequestException($message);
            }
            
            $contatoCliente = $this->paginate($this->ContatoCliente->find()
            );

            $this->set([
                'data' => [
                    'contatoCliente' => $contatoCliente,
                ],
            ]);
            $this->viewBuilder()->setOption('serialize', true);
        } catch (BadRequestException $e) {
            return $this->ErrorHandler->errorHandlerMessage($e, 400);
        } catch (NotFoundException $e) { 
            return $this->ErrorHandler->errorHandlerMessage($e, 404);
        } catch (Exception $e) {
            return $this->ErrorHandler->errorHandler($e, 500);
        }
    }

    public function view($id = null)
    {
        try {
            $authUser = $this->Auth->User();

            $conditions = null;
            if($authUser['tipo_usuario'] != 2){
                $conditions = [
                    'codigo_cliente' => $authUser['codigo']
                ];
            }

            $contatoCliente = $this->ContatoCliente->findByCodigo($id)
                                                   ->where($conditions)
                                                   ->first();

            if(!$contatoCliente){
                $dados = ['contatoCliente' => ['_error' => 'Registro não encontrado.']];
                throw new NotFoundException(json_encode($dados));
            }

            $this->set([
                'data' => [
                    'contatoCliente' => $contatoCliente,
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

            if ($this->request->is('post')) {
                $contatoCliente = $this->ContatoCliente->newEmptyEntity();
                $contatoCliente = $this->ContatoCliente->patchEntity($contatoCliente, $data);
                
                if ($this->ContatoCliente->save($contatoCliente)) {
                    $message = 'Salvo com sucesso!';
                } else {
                    $message = ['contatoCliente' => $contatoCliente->getErrors()];
                    throw new BadRequestException(json_encode($message));
                }
            }

            $this->set([
                'data' => [
                    'message' => $message,
                    'contatoCliente' => $contatoCliente,
                ],
            ]);
            $this->viewBuilder()->setOption('serialize', true);

        } catch (BadRequestException $e) { //400
            return $this->ErrorHandler->errorHandler($e, 400);
        } catch (Exception $e) {
            return $this->ErrorHandler->errorHandler($e, 500);
        }
    }

    public function edit($id = null)
    {
        $data = $this->request->getData();

        try {
            $contatoCliente = $this->ContatoCliente->findByCodigo($id)->first();
            if(!$contatoCliente){
                $dados = ['contatoCliente' => ['_error' => 'Registro não encontrado.']];
                throw new NotFoundException(json_encode($dados));
            }
            if ($this->request->is(['put'])) {
                $contatoCliente = $this->ContatoCliente->patchEntity($contatoCliente, $data);
                if ($this->ContatoCliente->save($contatoCliente)) {
                    $message = 'Editado com sucesso!';
                } else {
                    $message = ['contatoCliente' => $contatoCliente->getErrors()];
                    throw new BadRequestException(json_encode($message));
                }
            }
            $this->set([
                'data' => [
                    'message' => $message,
                    'contatoCliente' => $contatoCliente,
                ],
            ]);
            $this->viewBuilder()->setOption('serialize', true);
        } catch (BadRequestException $e) {
            return $this->ErrorHandler->errorHandler($e, 400);
        } catch (NotFoundException $e) {
            return $this->ErrorHandler->errorHandler($e, 404);
        } catch (Exception $e) {
            return $this->ErrorHandler->errorHandler($e, 500);
        }
    }

    public function delete($id = null)
    {
        try {

            $contatoCliente = $this->ContatoCliente->findByCodigo($id)->first();
            
            if(!$contatoCliente){
                $dados = ['contatoCliente' => ['_error' => 'Registro não encontrado.']];
                throw new NotFoundException(json_encode($dados));
            }
            
            if ($this->ContatoCliente->delete($contatoCliente)) {
                $message = 'Deletado com sucesso!';
            } else {
                $message = $contatoCliente->getErrors();
            }
            $this->set([
                'data' => [
                    'message' => $message,
                ],
            ]);
            $this->viewBuilder()->setOption('serialize', true);

        } catch (NotFoundException $e) {
            return $this->ErrorHandler->errorHandler($e, 404);
        } catch (Exception $e) {
            if($e->getCode() == 23000){
                return $this->ErrorHandler->errorHandlerConstraintViolation($e, 400);
            }
            return $this->ErrorHandler->errorHandler($e, 500);
        }
    }

  
}
