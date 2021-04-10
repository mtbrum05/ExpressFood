<?php

use Phinx\Seed\AbstractSeed;

class ContatoClienteSeeder extends AbstractSeed
{

    public function getDependencies()
    {
        return [
            'ContatoSeeder',
            'ClienteSeeder'
        ];
    }
    
    public function run()
    {
        $dados = [
            [
                'descricao' => '999999999',
                'codigo_cliente' => 1,
                'codigo_contato' => 1
            ],
            [
                'descricao' => '33333333',
                'codigo_cliente' => 2,
                'codigo_contato' => 2
            ],
            [
                'descricao' => '944443333',
                'codigo_cliente' => 2,
                'codigo_contato' => 1
            ],
        ];

        $contato_cliente = $this->table('contato_cliente');
        $contato_cliente->insert($dados)
              ->save();
   
    }
}
