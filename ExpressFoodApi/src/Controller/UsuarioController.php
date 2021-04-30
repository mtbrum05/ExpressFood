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
            $extraUser = $this->Usuario->findByCodigo($user['codigo'])
                        ->contain(['Empresa','Cliente'])
                        ->first();
            if (!$user) {
               $this->response = $this->response->withStatus(400);
                $message[] = 'Usuário ou senha inválidos';
                $this->set(compact('message'));
                $this->set('_serialize', ['message']);
                return;
            }

            if($extraUser->cliente['codigo']){
                $data = [
                    'token' => $token = \Firebase\JWT\JWT::encode([
                        'codigo' => $user['codigo'],
                        'tipo_usuario' => $user['tipo_usuario'],
                        'login' => $user['login'],
                        'email' => $user['email'],
                        'codigo_cliente' => $extraUser->cliente['codigo'] ? $extraUser->cliente['codigo'] : null,
                        'exp' => time() + 3600,
                            ], Security::getSalt()),
                        ];      
            }else if($extraUser->empresa['codigo']){
                $data = [
                    'token' => $token = \Firebase\JWT\JWT::encode([
                        'codigo' => $user['codigo'],
                        'tipo_usuario' => $user['tipo_usuario'],
                        'login' => $user['login'],
                        'email' => $user['email'],
                        'codigo_empresa' => $extraUser->empresa['codigo'] ? $extraUser->empresa['codigo'] : null,
                        'exp' => time() + 3600,
                            ], Security::getSalt()),
                        ];      
            } else {
                $data = [
                    'token' => $token = \Firebase\JWT\JWT::encode([
                        'codigo' => $user['codigo'],
                        'tipo_usuario' => $user['tipo_usuario'],
                        'login' => $user['login'],
                        'email' => $user['email'],
                        'exp' => time() + 3600,
                            ], Security::getSalt()),
                        ];      
            }
          $this->Auth->setUser($user);
          $this->set([
                    'success' => true,
                    'data' => $data,
                    '_serialize' => ['success', 'data'],
         ]);
        }
    }
}
