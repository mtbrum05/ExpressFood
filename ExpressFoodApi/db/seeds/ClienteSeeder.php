<?php


use Phinx\Seed\AbstractSeed;

class ClienteSeeder extends AbstractSeed
{

    public function run()
    {
        $dados = [
            [
            'nome'      => 'Matheus',
            'sobrenome' => 'Brum Dias',
            'cpf'       => '78918273037',
            'sexo'      => 'M',
            'email'     => 'mbrum@email.com',
            'senha'     => md5('123456')
            ],
            [
                'nome'      => 'José',
                'sobrenome' => 'Brum Dias',
                'cpf'       => '22996775007',
                'sexo'      => 'M',
                'email'     => 'jose_brum@email.com',
                'senha'     => md5('abc1234')
            ],
        ];

        $cliente = $this->table('cliente');
        $cliente->insert($dados)
              ->save();
    }
}
