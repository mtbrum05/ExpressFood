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
    public function validadorTipoUsuarioCliente($dados)
    {
        if($dados['tipo_usuario'] != 2){
            if(isset($dados['codigo_cliente'])){
                return [
                    'codigo_cliente' => $dados['codigo_cliente']
                ];
            } else {
                return null;
            }
        }else {
            return null;
        }
    }
    public function validadorTipoUsuarioEmpresa($dados)
    {
        if($dados['tipo_usuario'] != 2){
            if(isset($dados['codigo_empresa'])){
                return [
                    'codigo_empresa' => $dados['codigo_empresa']
                ];
            } else {
                return null;
            }
        }else {
            return null;
        }
    }

}
