<?php
declare(strict_types=1);

namespace App\Controller;

use Exception;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Exception\BadRequestException;

/**
 * EnderecoCliente Controller
 *
 * @property \App\Model\Table\EnderecoClienteTable $EnderecoCliente
 * @method \App\Model\Entity\EnderecoCliente[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EnderecoClienteController extends AppController
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
            if(!empty($queryString['descricao_endereco'])){
                $this->paginate['conditions']['descricao_endereco LIKE'] = '%'.$queryString['descricao_endereco'].'%';          
            } 
            if(!empty($queryString['codigo_cliente'])){
                $this->paginate['conditions']['codigo_cliente'] = $queryString['codigo_cliente'];          
            }
            
            $enderecoCliente = $this->paginate($this->EnderecoCliente->find()
                                                                   ->order(['principal'=> 'DESC'])
                                                                );

            $this->set([
                'data' => [
                    'enderecoCliente' => $enderecoCliente,
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

            $enderecoCliente = $this->EnderecoCliente->findByCodigo($id)
                                                   ->where($conditions)
                                                   ->first();

            if(!$enderecoCliente){
                $dados = ['enderecoCliente' => ['_error' => 'Registro não encontrado.']];
                throw new NotFoundException(json_encode($dados));
            }

            $this->set([
                'data' => [
                    'enderecoCliente' => $enderecoCliente,
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

            $retorno = $this->EnderecoCliente->adicionarEndereco($data);

            $this->set([
                'data' => [
                    'message' => $retorno['message'],
                    'enderecoCliente' => $retorno['enderecoCliente'],
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

    public function edit($id = null)
    {
        try {

            $data = $this->request->getData();

            $retorno = $this->EnderecoCliente->editarEndereco($id,$data);

            $this->set([
                'data' => [
                    'message' => $retorno['message'],
                    'enderecoCliente' => $retorno['enderecoCliente'],
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

            $enderecoCliente = $this->EnderecoCliente->findByCodigo($id)->first();
            
            if(!$enderecoCliente){
                $dados = ['enderecoCliente' => ['_error' => 'Registro não encontrado.']];
                throw new NotFoundException(json_encode($dados));
            }
            
            if ($this->EnderecoCliente->delete($enderecoCliente)) {
                $message = 'Deletado com sucesso!';
            } else {
                $message = $enderecoCliente->getErrors();
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
