<?php


use Phinx\Seed\AbstractSeed;

class EmpresaSeeder extends AbstractSeed
{
    public function getDependencies()
    {
        return [
            'UsuarioSeeder',
        ];
    }
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $dados = [
            [
                'razao_social'   => 'ARCOS DOURADOS COMERCIO DE ALIMENTOS LTDA',
                'nome_fantasia'  => 'MCDONALD´S',
                'cnpj'           => '42591651000143',
                'ativo'          => true,
                'codigo_usuario' => 3                
            ],
            [
                'razao_social'   => 'PODIUM COMERCIO DE ALIMENTOS LTDA',
                'nome_fantasia'  => 'HABIB´S',
                'cnpj'           => '07918283000102',
                'ativo'          => true,
                'codigo_usuario' => 4
            ],
        ];

        $empresa = $this->table('empresa');
        $empresa->insert($dados)
              ->save();
    }
}
