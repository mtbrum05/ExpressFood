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
