<?php
declare(strict_types=1);

namespace App\Controller;

use Exception;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Exception\BadRequestException;

/**
 * ContatoEmpresa Controller
 *
 * @property \App\Model\Table\ContatoEmpresaTable $ContatoEmpresa
 * @method \App\Model\Entity\ContatoEmpresa[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ContatoEmpresaController extends AppController
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
                if (isset($authUser['codigo_cliente'])){
                    $message = 'Registro disponivel apenas para Administradores';
                    throw new BadRequestException($message);
                }
                $this->paginate['conditions']['codigo_empresa'] = $authUser['codigo_empresa'];
            }
            if(!empty($queryString['descricao'])){
                $this->paginate['conditions']['descricao LIKE'] = '%'.$queryString['descricao'].'%';          
            } 
            if(!empty($queryString['codigo_empresa'])){
                $this->paginate['conditions']['codigo_empresa'] = $queryString['codigo_empresa'];          
            } 
            if(isset($queryString['ativo'])){
                $ativo = $this->Paginador->validadorAtivo($queryString['ativo']);
                $this->paginate['conditions']['ativo'] = $ativo;
            }
            
            $contatoEmpresa = $this->paginate($this->ContatoEmpresa->find());

            $this->set([
                'data' => [
                    'contatoEmpresa' => $contatoEmpresa,
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

            $conditions = $this->Paginador->validadorTipoUsuarioEmpresa($authUser);

            $contatoEmpresa = $this->ContatoEmpresa->findByCodigo($id)
                                                   ->where($conditions)
                                                   ->first();

            if(!$contatoEmpresa){
                $dados = ['contatoEmpresa' => ['_error' => 'Registro não encontrado.']];
                throw new NotFoundException(json_encode($dados));
            }

            $this->set([
                'data' => [
                    'contatoEmpresa' => $contatoEmpresa,
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
                $contatoEmpresa = $this->ContatoEmpresa->newEmptyEntity();
                $contatoEmpresa = $this->ContatoEmpresa->patchEntity($contatoEmpresa, $data);
                
                if ($this->ContatoEmpresa->save($contatoEmpresa)) {
                    $message = 'Salvo com sucesso!';
                } else {
                    $message = ['contatoEmpresa' => $contatoEmpresa->getErrors()];
                    throw new BadRequestException(json_encode($message));
                }
            }

            $this->set([
                'data' => [
                    'message' => $message,
                    'contatoEmpresa' => $contatoEmpresa,
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
            $contatoEmpresa = $this->ContatoEmpresa->findByCodigo($id)->first();
            if(!$contatoEmpresa){
                $dados = ['contatoEmpresa' => ['_error' => 'Registro não encontrado.']];
                throw new NotFoundException(json_encode($dados));
            }
            if ($this->request->is(['put'])) {
                $contatoEmpresa = $this->ContatoEmpresa->patchEntity($contatoEmpresa, $data);
                if ($this->ContatoEmpresa->save($contatoEmpresa)) {
                    $message = 'Editado com sucesso!';
                } else {
                    $message = ['contatoEmpresa' => $contatoEmpresa->getErrors()];
                    throw new BadRequestException(json_encode($message));
                }
            }
            $this->set([
                'data' => [
                    'message' => $message,
                    'contatoEmpresa' => $contatoEmpresa,
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

            $contatoEmpresa = $this->ContatoEmpresa->findByCodigo($id)->first();
            
            if(!$contatoEmpresa){
                $dados = ['contatoEmpresa' => ['_error' => 'Registro não encontrado.']];
                throw new NotFoundException(json_encode($dados));
            }
            
            if ($this->ContatoEmpresa->delete($contatoEmpresa)) {
                $message = 'Deletado com sucesso!';
            } else {
                $message = $contatoEmpresa->getErrors();
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
