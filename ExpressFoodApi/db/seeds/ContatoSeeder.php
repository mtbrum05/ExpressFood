<?php

use Phinx\Seed\AbstractSeed;

class ContatoSeeder extends AbstractSeed
{
    public function run()
    {
        $dados = [
            [
                'descricao' => 'Celular',
            ],
            [
                'descricao' => 'Telefone Fixo',
            ],
            [
                'descricao' => 'Empresarial',
            ],
            [
                'descricao' => 'Outros',
            ],
        ];

        $contato = $this->table('contato');
        $contato->insert($dados)
              ->save();
    }
}
