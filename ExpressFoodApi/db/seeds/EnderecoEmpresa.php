<?php


use Phinx\Seed\AbstractSeed;

class EnderecoEmpresa extends AbstractSeed
{
    public function getDependencies()
    {
        return [
            'UsuarioSeeder',
            'EmpresaSeeder'
        ];
    }
    public function run()
    {
        $dados = [
            [
                'descricao_endereco' => 'Av. Paulista',
                'numero'             => 810,
                'bairro'             => 'Bela Vista',
                'cep'                => '01310100',
                'cidade'             => 'São Paulo',
                'estado'             => 'São Paulo',
                'pais'               => 'Brasil',
                'codigo_empresa'     => 1,
                'principal'          => 1
            ],
            [
                'descricao_endereco' => 'Av. Antônio Piranga',
                'numero'             => 703,
                'bairro'             => 'Centro',
                'cep'                => '09911160',
                'cidade'             => 'Diadema',
                'estado'             => 'São Paulo',
                'pais'               => 'Brasil',
                'codigo_empresa'     => 2,
                'principal'          => 1
            ],

        ];

        $endereco_empresa = $this->table('endereco_empresa');
        $endereco_empresa->insert($dados)
              ->save();
    }
}
