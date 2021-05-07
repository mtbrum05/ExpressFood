<?php
declare(strict_types=1);

namespace App\Controller;

use Exception;
use Cake\Http\Exception\BadRequestException;
use League\Container\Exception\NotFoundException;

/**
 * EnderecoEmpresa Controller
 *
 * @property \App\Model\Table\EnderecoEmpresaTable $EnderecoEmpresa
 * @method \App\Model\Entity\EnderecoEmpresa[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class EnderecoEmpresaController extends AppController
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
            if(!empty($queryString['descricao_endereco'])){
                $this->paginate['conditions']['descricao_endereco LIKE'] = '%'.$queryString['descricao_endereco'].'%';          
            } 
            if(!empty($queryString['codigo_empresa'])){
                $this->paginate['conditions']['codigo_empresa'] = $queryString['codigo_empresa'];          
            }
            
            $enderecoEmpresa = $this->paginate($this->EnderecoEmpresa->find()
                                                                   ->order(['principal'=> 'DESC'])
                                                                );

            $this->set([
                'data' => [
                    'enderecoEmpresa' => $enderecoEmpresa,
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

            $enderecoEmpresa = $this->EnderecoEmpresa->findByCodigo($id)
                                                   ->where($conditions)
                                                   ->first();

            if(!$enderecoEmpresa){
                $dados = ['enderecoEmpresa' => ['_error' => 'Registro não encontrado.']];
                throw new NotFoundException(json_encode($dados));
            }

            $this->set([
                'data' => [
                    'enderecoEmpresa' => $enderecoEmpresa,
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

            $retorno = $this->EnderecoEmpresa->adicionarEndereco($data);

            $this->set([
                'data' => [
                    'message' => $retorno['message'],
                    'enderecoEmpresa' => $retorno['enderecoEmpresa'],
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

            $retorno = $this->EnderecoEmpresa->editarEndereco($id,$data);

            $this->set([
                'data' => [
                    'message' => $retorno['message'],
                    'enderecoEmpresa' => $retorno['enderecoEmpresa'],
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

            $enderecoEmpresa = $this->EnderecoEmpresa->findByCodigo($id)->first();
            
            if(!$enderecoEmpresa){
                $dados = ['enderecoEmpresa' => ['_error' => 'Registro não encontrado.']];
                throw new NotFoundException(json_encode($dados));
            }
            
            if ($this->EnderecoEmpresa->delete($enderecoEmpresa)) {
                $message = 'Deletado com sucesso!';
            } else {
                $message = $enderecoEmpresa->getErrors();
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
