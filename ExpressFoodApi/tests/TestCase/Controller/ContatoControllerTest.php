<?php

namespace App\Test\TestCase\Controller;

use App\Controller\ContatoController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\ContatoController Test Case
 *
 * @uses \App\Controller\ContatoController
 */
class ContatoControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Cliente',
        'app.Contato',
        'app.ContatoCliente',
    ];

    private function login()
    {
        $data = [
            'email' => 'teste@teste.com.br',
            'senha' => '123456'
        ];

        $this->post('login', $data);
        $token = json_decode($this->_response->getBody(), true);

        $this->configRequest([
            'headers' => [
                'Authorization' => 'Bearer ' . $token['data']['token']
            ],
        ]);
    }

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndexRetornoSucesso() : void
    {
        $this->login();

        $this->get('contato');
        $retorno = json_decode($this->_response->getBody(), true);

        $this->assertArrayHasKey('data', $retorno);
        $this->assertArrayHasKey('contato', $retorno['data']);
        $this->assertResponseNotEmpty();
        $this->assertResponseCode(200);
    }

    public function testIndexRetornoSucessoFiltroDescricao() : void
    {
        $this->login();

        $this->get('contato?descricao=outros');
        $retorno = json_decode($this->_response->getBody(), true);

        $this->assertArrayHasKey('data', $retorno);
        $this->assertArrayHasKey('contato', $retorno['data']);
        $this->assertResponseNotEmpty();
        $this->assertResponseCode(200);
    }


    public function testIndexRetornoSucessoFiltroAtivo()
    {
        $this->login();

        $this->get('contato?ativo=1');
        $retorno = json_decode($this->_response->getBody(), true);

        $this->assertArrayHasKey('data', $retorno);
        $this->assertArrayHasKey('contato', $retorno['data']);
        $this->assertResponseNotEmpty();
        $this->assertResponseCode(200);
    }

    public function testViewSucesso()
    {
        $this->login();

        $this->get('contato/1');
        $retorno = json_decode($this->_response->getBody(), true);
        $esperado = [
            'data' => [
                'contato' => [
                    'codigo' => (int) 1,
                    'ativo' => true
                ]
            ]
        ];
        $this->assertEquals($esperado['data']['contato']['codigo'], $retorno['data']['contato']['codigo']);
        $this->assertArrayHasKey('data', $retorno);
        $this->assertArrayHasKey('contato', $retorno['data']);
        $this->assertResponseNotEmpty();
        $this->assertResponseCode(200);
    }

    public function testViewNotFound()
    {
        $this->login();

        $this->get('contato/999');
        $retorno = json_decode($this->_response->getBody(), true);
        $esperado = [
            'data' => null,
            'message' => [
                'contato' => [
                    '_error' => 'Registro não encontrado.'
                ]
            ]
        ];
        $this->assertEquals($esperado, $retorno);
        $this->assertArrayHasKey('data', $retorno);
        $this->assertArrayHasKey('contato', $retorno['message']);
        $this->assertResponseNotEmpty();
        $this->assertResponseCode(404);
    }

    public function testAddSucesso()
    {
        $this->login();

        $data = [
            'descricao' => 'testAdd',
            'data_criacao' => '2021-04-06 01:52:43',
            'data_modificacao' => '2021-04-06 01:52:43',
            'ativo' => true
        ];

        $this->post('contato', $data);
        $retorno = json_decode($this->_response->getBody(), true);

        $esperado = [
            'data' => [
                'message' => 'Salvo com sucesso!',
                'contato' => [
                    'descricao' => 'testAdd',
                    'data_criacao' => '2021-04-06T01:52:43+00:00',
                    'data_modificacao' => '2021-04-06T01:52:43+00:00',
                    'ativo' => true,
                    'codigo' => (int) 3
                ]
            ]
        ];

        $this->assertEquals($esperado, $retorno);
        $this->assertArrayHasKey('data', $retorno);
        $this->assertArrayHasKey('contato', $retorno['data']);
        $this->assertResponseNotEmpty();
        $this->assertResponseCode(200);
    }

    public function testAddDescricaoUnica()
    {
        $this->login();

        //Adicionando outro contato com a mesma descrição do código 1
        $data = [
            'descricao' => 'Telefone',
            'data_criacao' => '2021-04-06 01:52:43',
            'data_modificacao' => '2021-04-06 01:52:43',
            'ativo' => true
        ];

        $this->post('contato', $data);
        $retorno = json_decode($this->_response->getBody(), true);

        $esperado = [
            'data' => null,
            'message' => [
                'contato' => [
                    'descricao' => [
                        '_isUnique' => 'descrição já cadastrada no banco.'
                    ]
                ]
            ]
        ];

        $this->assertEquals($esperado, $retorno);
        $this->assertArrayHasKey('data', $retorno);
        $this->assertArrayHasKey('contato', $retorno['message']);
        $this->assertResponseNotEmpty();
        $this->assertResponseCode(400);
    }

    public function testEditSucesso()
    {
        $this->login();

        $data = [
            'descricao' => 'test Editado',
            'data_criacao' => '2021-04-06 01:52:43',
            'data_modificacao' => '2021-04-06 01:52:43',
            'ativo' => true
        ];

        $this->put('contato/2', $data);
        $retorno = json_decode($this->_response->getBody(), true);

        $esperado = [
            'data' => [
                'message' => 'Editado com sucesso!',
                'contato' => [
                    'descricao' => 'test Editado',
                    'data_criacao' => '2021-04-06T01:52:43+00:00',
                    'data_modificacao' => '2021-04-06T01:52:43+00:00',
                    'ativo' => true,
                    'codigo' => (int) 2
                ]
            ]
        ];

        $this->assertEquals($esperado, $retorno);
        $this->assertArrayHasKey('data', $retorno);
        $this->assertArrayHasKey('contato', $retorno['data']);
        $this->assertResponseNotEmpty();
        $this->assertResponseCode(200);
    }

    public function testEditContatoNaoencontrado()
    {
        $this->login();

        $data = [
            'descricao' => 'test editado não encontrado',
            'data_criacao' => '2021-04-06 01:52:43',
            'data_modificacao' => '2021-04-06 01:52:43',
            'ativo' => true
        ];

        $this->put('contato/999', $data);
        $retorno = json_decode($this->_response->getBody(), true);

        $esperado = [
            'data' => null,
            'message' => [
                'contato' => [
                    '_error' => 'Registro não encontrado.'
                ]
            ]
        ];

        $this->assertEquals($esperado, $retorno);
        $this->assertArrayHasKey('data', $retorno);
        $this->assertArrayHasKey('contato', $retorno['message']);
        $this->assertResponseNotEmpty();
        $this->assertResponseCode(404);
    }

    public function testEditDescricaoUnica()
    {
        $this->login();

        //Registro 2 tentando ter a mesma descricao do registro 1
        $data = [
            'descricao' => 'Telefone',
            'data_criacao' => '2021-04-06 01:52:43',
            'data_modificacao' => '2021-04-06 01:52:43',
            'ativo' => true
        ];

        $this->put('contato/2', $data);
        $retorno = json_decode($this->_response->getBody(), true);

        $esperado = [
            'data' => null,
            'message' => [
                'contato' => [
                    'descricao' => [
                        '_isUnique' => 'descrição já cadastrada no banco.'
                    ]
                ]
            ]
        ];

        $this->assertEquals($esperado, $retorno);
        $this->assertArrayHasKey('data', $retorno);
        $this->assertArrayHasKey('contato', $retorno['message']);
        $this->assertResponseNotEmpty();
        $this->assertResponseCode(400);
    }

    //Executar os testes de delete sempre por ultimo na esteira
    public function testDeleteSucesso()
    {
        $this->login();

        $this->delete('contato/2');
        $retorno = json_decode($this->_response->getBody(), true);

        $esperado = [
            'data' => [
                'message' => 'Deletado com sucesso!'
            ]
        ];

        $this->assertEquals($esperado, $retorno);
        $this->assertArrayHasKey('data', $retorno);
        $this->assertEquals('Deletado com sucesso!', $retorno['data']['message']);
        $this->assertResponseNotEmpty();
        $this->assertResponseCode(200);
    }

    public function testDeleteContatoNaoEncontrado()
    {
        $this->login();

        $this->delete('contato/999');
        $retorno = json_decode($this->_response->getBody(), true);

        $esperado = [
            'data' => null,
            'message' => [
                'contato' => [
                    '_error' => 'Registro não encontrado.'
                ]
            ]
        ];

        $this->assertEquals($esperado, $retorno);
        $this->assertArrayHasKey('data', $retorno);
        $this->assertArrayHasKey('contato', $retorno['message']);
        $this->assertResponseNotEmpty();
        $this->assertResponseCode(404);
    }
}
