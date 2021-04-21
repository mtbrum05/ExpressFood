<?php


use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;


/** @var \Cake\Routing\RouteBuilder $routes */
$routes->setRouteClass(DashedRoute::class);

$routes->scope('/', function (RouteBuilder $builder) {   

    $builder->post('login',['controller' => 'Cliente','action' => 'login']);
    $builder->resources('Contato', ['path' => 'contato']);  
    $builder->resources('Cliente', ['path' => 'cliente']);   
    $builder->connect('/', ['controller' => 'Swagger', 'action' => 'index', 'plugin' => 'SwaggerCustom']);


    
    $builder->fallbacks();
});

