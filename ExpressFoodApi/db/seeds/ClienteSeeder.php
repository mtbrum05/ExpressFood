<?php


use Phinx\Seed\AbstractSeed;
use Cake\Auth\DefaultPasswordHasher;

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
            'senha'     => password_hash('123456',PASSWORD_DEFAULT)
            ],
            [
                'nome'      => 'José',
                'sobrenome' => 'Brum Dias',
                'cpf'       => '22996775007',
                'sexo'      => 'M',
                'email'     => 'jose_brum@email.com',
                'senha'     => password_hash('abc1234',PASSWORD_DEFAULT)
            ],
        ];

        $cliente = $this->table('cliente');
        $cliente->insert($dados)
              ->save();
    }
}
