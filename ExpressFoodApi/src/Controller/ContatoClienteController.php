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

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Paginador');
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
                $this->paginate['conditions']['codigo_cliente'] = $authUser['codigo_cliente'];
            }
            if(!empty($queryString['descricao'])){
                $this->paginate['conditions']['descricao LIKE'] = '%'.$queryString['descricao'].'%';          
            } 
            if(!empty($queryString['codigo_cliente'])){
                $this->paginate['conditions']['codigo_cliente'] = $queryString['codigo_cliente'];          
            } 
            if(isset($queryString['ativo'])){
                $ativo = $this->Paginador->validadorAtivo($queryString['ativo']);
                $this->paginate['conditions']['ativo'] = $ativo;
            }
            
            $contatoCliente = $this->paginate($this->ContatoCliente->find());

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

            $conditions = $this->Paginador->validadorTipoUsuarioCliente($authUser);

            $contatoCliente = $this->ContatoCliente->findByCodigo($id)
                                                   ->where($conditions)
                                                   ->first();

            if(!$contatoCliente){
                $dados = ['contatoCliente' => ['_error' => 'Registro n??o encontrado.']];
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
                $dados = ['contatoCliente' => ['_error' => 'Registro n??o encontrado.']];
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
                $dados = ['contatoCliente' => ['_error' => 'Registro n??o encontrado.']];
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
