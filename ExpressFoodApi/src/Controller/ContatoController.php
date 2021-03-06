<?php
namespace App\Controller;

use Exception;
use App\Controller\AppController;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Exception\BadRequestException;

/**
 * Contato Controller
 *
 * @property \App\Model\Table\ContatoTable $Contato
 *
 * @method \App\Model\Entity\Contato[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ContatoController extends AppController
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
                'limit' => 20,
                'conditions' => array(),
            );

            $queryString = $this->getRequest()->getQueryParams();
            
            if(!empty($queryString['descricao'])){
                $this->paginate['conditions']['descricao LIKE'] = '%'.$queryString['descricao'].'%';          
            }      
            if(isset($queryString['ativo'])){
                $ativo = $this->Paginador->validadorAtivo($queryString['ativo']);
                $this->paginate['conditions']['ativo'] = $ativo;
            }

            $contato = $this->paginate($this->Contato->find());

            $this->set([
                'data' => [
                    'contato' => $contato,
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
            $contato = $this->Contato->findByCodigo($id)->first();
            if(!$contato){
                $dados = ['contato' => ['_error' => 'Registro não encontrado.']];
                throw new NotFoundException(json_encode($dados));
            }

            $this->set([
                'data' => [
                    'contato' => $contato,
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


        $data = $this->request->getData();

        try {

            if ($this->request->is('post')) {
                $contato = $this->Contato->newEmptyEntity();
                $contato = $this->Contato->patchEntity($contato, $data);
                
                if ($this->Contato->save($contato)) {
                    $message = 'Salvo com sucesso!';
                } else {
                    $message = ['contato' => $contato->getErrors()];
                    throw new BadRequestException(json_encode($message));
                }
            }
            $this->set([
                'data' => [
                    'message' => $message,
                    'contato' => $contato,
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
            $contato = $this->Contato->findByCodigo($id)->first();
            if(!$contato){
                $dados = ['contato' => ['_error' => 'Registro não encontrado.']];
                throw new NotFoundException(json_encode($dados));
            }
            if ($this->request->is(['put'])) {
                $contato = $this->Contato->patchEntity($contato, $data);
                if ($this->Contato->save($contato)) {
                    $message = 'Editado com sucesso!';
                } else {
                    $message = ['contato' => $contato->getErrors()];
                    throw new BadRequestException(json_encode($message));
                }
            }
            $this->set([
                'data' => [
                    'message' => $message,
                    'contato' => $contato,
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

            $contato = $this->Contato->findByCodigo($id)->first();
            
            if(!$contato){
                $dados = ['contato' => ['_error' => 'Registro não encontrado.']];
                throw new NotFoundException(json_encode($dados));
            }
            
            if ($this->Contato->delete($contato)) {
                $message = 'Deletado com sucesso!';
            } else {
                $message = $contato->getErrors();
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
