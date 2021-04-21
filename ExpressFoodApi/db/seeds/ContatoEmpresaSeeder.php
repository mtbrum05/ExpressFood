<?php


use Phinx\Seed\AbstractSeed;

class ContatoEmpresaSeeder extends AbstractSeed
{
    public function getDependencies()
    {
        return [
            'ContatoSeeder',
            'EmpresaSeeder'
        ];
    }
    
    public function run()
    {
        $dados = [
            [
                'descricao' => '222222222',
                'codigo_empresa' => 1,
                'codigo_contato' => 1
            ],
            [
                'descricao' => '33333333',
                'codigo_empresa' => 2,
                'codigo_contato' => 2
            ],
            [
                'descricao' => '444444444',
                'codigo_empresa' => 2,
                'codigo_contato' => 1
            ],
        ];

        $contato_empresa = $this->table('contato_empresa');
        $contato_empresa->insert($dados)
              ->save();
   
    }
}
