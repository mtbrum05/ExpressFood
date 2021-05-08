<?php


use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;


/** @var \Cake\Routing\RouteBuilder $routes */
$routes->setRouteClass(DashedRoute::class);

$routes->scope('/', function (RouteBuilder $builder) {   

    $builder->post('login',['controller' => 'Usuario','action' => 'login']);
    $builder->resources('Contato', ['path' => 'contato']);  
    $builder->resources('Cliente', ['path' => 'cliente']);  
    $builder->resources('Empresa', ['path' => 'empresa']);  
    $builder->resources('ContatoCliente', ['path' => 'contato_cliente']);   
    $builder->resources('ContatoEmpresa', ['path' => 'contato_empresa']);   
    $builder->resources('EnderecoCliente', ['path' => 'endereco_cliente']);   
    $builder->resources('EnderecoEmpresa', ['path' => 'endereco_empresa']);   

    $builder->connect('/', ['controller' => 'Swagger', 'action' => 'index', 'plugin' => 'SwaggerCustom']);


    
    $builder->fallbacks();
});

