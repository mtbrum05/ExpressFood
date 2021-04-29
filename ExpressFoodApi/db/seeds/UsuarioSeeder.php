<?php


use Phinx\Seed\AbstractSeed;

class UsuarioSeeder extends AbstractSeed
{
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
            'email'         => 'mbrum@email.com',
            'login'         => 'mbrum',
            'senha'         => password_hash('123456',PASSWORD_DEFAULT),
            'tipo_usuario'  => 0,       
            'foto'          => null
            ],
            [
            'email'         => 'jose_brum@email.com',
            'login'         => 'jose10',
            'senha'         => password_hash('abc1234',PASSWORD_DEFAULT),
            'tipo_usuario'  => 0,       
            'foto'          => null
            ],
            [
                'email'         => 'mcdonalds@mcdonalds.com',
                'login'         => 'mc10',
                'senha'         => password_hash('mcDonalds37',PASSWORD_DEFAULT),
                'tipo_usuario'  => 1,       
                'foto'          => null
            ],
            [
                'email'         => 'habibs@habibs.com',
                'login'         => 'habibsMatriz1',
                'senha'         => password_hash('habibsMatriz1@.',PASSWORD_DEFAULT),
                'tipo_usuario'  => 1,       
                'foto'          => null
            ],
            [
                'email'         => 'admin@admin.com',
                'login'         => 'admin05',
                'senha'         => password_hash('admin1',PASSWORD_DEFAULT),
                'tipo_usuario'  => 2,       
                'foto'          => null
            ],
        ];

        $usuario = $this->table('usuario');
        $usuario->insert($dados)
              ->save();        
    }
}
