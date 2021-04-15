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

            $contato = $this->paginate($this->Contato->find());
            $this->set([
                'data' => [
                    'contato' => $contato,
                ],
            ]);
            $this->viewBuilder()->setOption('serialize', true);
        } catch (BadRequestException $e) { //400

            $dados = [
                "data"      => null,
                "message" => $e->getMessage()
            ];

            return $this->response
                ->withStatus(400)
                ->withType('application/json')
                ->withStringBody(json_encode($dados));
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
            $dados = [
                "data"      => null,
                "message" => json_decode($e->getMessage(), true)
            ];

            return $this->response
                ->withStatus(400)
                ->withType('application/json')
                ->withStringBody(json_encode($dados));
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
        } catch (BadRequestException $e) { //400
            $dados = [
                "data"      => null,
                "message" => json_decode($e->getMessage(), true)
            ];

            return $this->response
                ->withStatus(400)
                ->withType('application/json')
                ->withStringBody(json_encode($dados));
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
