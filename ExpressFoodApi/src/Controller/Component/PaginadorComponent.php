<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Http\Exception\BadRequestException;

class PaginadorComponent extends Component
{

    public function validadorAtivo($ativo)
    {
        if (isset($ativo) && is_numeric($ativo)) {
            if (in_array($ativo, array(0, 1))) {
                return $ativo;
            } else {
                $message = 'Ativo deve receber (1)true ou (0)false.';
                throw new BadRequestException($message);
            }
        } else if (isset($ativo) && !is_numeric($ativo)) {
            $message = 'Ativo deve receber (1)true ou (0)false.';
            throw new BadRequestException($message);
        }
    }

    public function validadorTipoUsuario($dados)
    {
        if($dados['tipo_usuario'] != 2){
            return [
                'codigo_cliente' => $dados['codigo']
            ];
        }else {
            return null;
        }
    }

}
