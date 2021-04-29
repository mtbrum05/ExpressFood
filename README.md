# ExpressFood
[Em construção]
Meu primeiro portfólio atuando como Desenvolvedor JR.

Este projeto tem por objetivo simular aplicativos como iFood, Rappi, Uber Eats, entre outros.

A ideia é que toda a comunicação com o backend seja realizada através do consumo de API's restful.
Sobre o frontend, a tecnologia ainda será escolhida entre React ou Vue, para aproveitar a minha idéia de estudar mais a fundo JS.

O backend será desenvolvido com a linguagem PHP 7.2.34 e utilizando CakePHP na versão 4.2.0.
Para banco de dados, estou utilizando mySQL e montando toda a estrutura e massa de dados a partir de migrations e seeders utilizando o phinx, e para consultas diretas ao banco estou utilizando o DBeaver 7.3.0.
Para realizar os consumos a API, estou utilizando o Postman e para versionamento o GIT.

Funcionalidades disponíveis:

Contato(Tipos) - Toda a parte de cadastro de contato(listagem, visualização, cadastro, edição e deleção) com o tratamento de erros específico para cada ocasião.
Contato será responsável por armazenar os dados de tipos de contato(Celular, Fixo, Empresarial, etc) disponíveis no momento em que um cliente estiver interagindo com seus próprios contatos.
Todas as funcionalidades estão com testes automáticos realizados.

Contatos do Cliente - Toda a parte de cadastro de contatos de cliente(listagem, visualização, cadastro, edição e deleção) com o tratamento de erros específico para cada ocasião.
Está funcionalidade será responsável por gerenciar os contatos do cliente em questão.
Os dados serão devidamente protegidos a cada cliente, ou seja, O cliente João jamais terá acesso a dados de contato da cliente Maria. Isto de seve a algumas validações feitas através da autenticação, feita em JWT, que não permite que um cliente acesse dados sensíveis de outro cliente.


Instruções para instalação:

- Faça um clone do projeto através do seguinte comando: git clone https://github.com/mtbrum05/ExpressFood.git
- Acesse a pasta da api e execute o seguinte comando: composer install
- Crie um banco de dados com o nome express_food, e realize as configurações do seu mySQL em app/config/app.php
- Rode as migrations através do seguinte comando: vendor/bin/phinx migrate
- Execute as seeders com o comando: vendor/bin/phinx seed:run
- Para se logar e gerar o token JWT, acesso o endpoint de login, com o metodo POST em: suamaquinalocal/ExpressFood/ExpressFoodApi/login, passando o payload:
            {
            "email": "admin@admin.com",
            "senha": "admin1"
            }
- Utilize o Token gerado para consumir as funcionalidades, tais como:
Contato(Tipos) = suamaquinalocal/ExpressFood/ExpressFoodApi/contato
ContatoCliente = suamaquinalocal/ExpressFood/ExpressFoodApi/contato_cliente