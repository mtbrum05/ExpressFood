<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Utility\Security;

/**
 * Usuario Controller
 *
 * @property \App\Model\Table\UsuarioTable $Usuario
 * @method \App\Model\Entity\Usuario[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsuarioController extends AppController
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
                            'codigo' => $user['codigo'],
                            'login' => $user['login'],
                            'email' => $user['email'],
                            'exp' => time() + 3600,
                                ], Security::getSalt()),
                    ],
                    '_serialize' => ['success', 'data'],
         ]);
        }
    }
}
