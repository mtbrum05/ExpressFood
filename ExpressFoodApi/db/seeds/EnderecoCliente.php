<?php


use Phinx\Seed\AbstractSeed;

class EnderecoCliente extends AbstractSeed
{

    public function getDependencies()
    {
        return [
            'UsuarioSeeder',
            'ClienteSeeder'
        ];
    }
    public function run()
    {
        $dados = [
            [
                'descricao_endereco' => 'Rua Teste',
                'numero'             => 200,
                'bairro'             => 'Jd. Sapopema',
                'cep'                => '12345180',
                'cidade'             => 'Diadema',
                'estado'             => 'São Paulo',
                'pais'               => 'Brasil',
                'codigo_cliente'     => 1,
                'principal'          => 1
            ],
            [
                'descricao_endereco' => 'Av. Paulista',
                'numero'             => 2137,
                'bairro'             => 'Centro',
                'cep'                => '01310100',
                'cidade'             => 'São Paulo',
                'estado'             => 'São Paulo',
                'pais'               => 'Brasil',
                'codigo_cliente'     => 2,
                'principal'          => 1
            ],

        ];

        $endereco_cliente = $this->table('endereco_cliente');
        $endereco_cliente->insert($dados)
              ->save();
    }
}
