<?php
declare(strict_types=1);

namespace App\Controller;

use Exception;
use Cake\Utility\Security;
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
        $this->Auth->allow('login');
    }

    public function login(): void
     {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if (!$user) {
               $this->response = $this->response->withStatus(400);
                $message[] = 'Usuário ou senha inválidos';
                $this->set(compact('message'));
                $this->set('_serialize', ['message']);
                return;
            }
          $this->Auth->setUser($user);
          $this->set([
                    'success' => true,
                    'data' => [
                        'token' => $token = \Firebase\JWT\JWT::encode([
                            'nome' => $user['nome'],
                            'sobrenome' => $user['sobrenome'],
                            'email' => $user['email'],
                            'exp' => time() + 3600,
                                ], Security::getSalt()),
                    ],
                    '_serialize' => ['success', 'data'],
         ]);
        }
    }


    public function add()
    {


        $data = $this->request->getData();

        try {

            if ($this->request->is('post')) {
                $cliente = $this->Cliente->newEntity();
                $cliente = $this->Cliente->patchEntity($cliente, $data);
                
                if ($this->Cliente->save($cliente)) {
                    $message = 'Salvo com sucesso!';
                } else {
                    $message = ['cliente' => $cliente->getErrors()];
                    throw new BadRequestException(json_encode($message));
                }
            }
            $this->set([
                'data' => [
                    'message' => $message,
                    'cliente' => $cliente,
                ],
                '_serialize' => ['data']
            ]);
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


    
}
