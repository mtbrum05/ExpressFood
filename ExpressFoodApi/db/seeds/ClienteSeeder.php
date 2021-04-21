<?php


use Phinx\Seed\AbstractSeed;
use Cake\Auth\DefaultPasswordHasher;

class ClienteSeeder extends AbstractSeed
{
    public function getDependencies()
    {
        return [
            'UsuarioSeeder',
        ];
    }

    public function run()
    {
        $dados = [
            [
                'nome'           => 'Matheus',
                'sobrenome'      => 'Brum Dias',
                'cpf'            => '78918273037',
                'sexo'           => 'M',
                'ativo'          => true,
                'codigo_usuario' => 1                
            ],
            [
                'nome'          => 'José',
                'sobrenome'     => 'Brum Dias',
                'cpf'           => '22996775007',
                'sexo'          => 'M',
                'ativo'         => false,
                'codigo_usuario' => 2
            ],
        ];

        $cliente = $this->table('cliente');
        $cliente->insert($dados)
              ->save();
    }
}
