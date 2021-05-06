<?php

namespace App\Model\Behavior;

use Cake\ORM\Behavior;

class ComumBehavior extends Behavior
{
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


?>