<?php


use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;


/** @var \Cake\Routing\RouteBuilder $routes */
$routes->setRouteClass(DashedRoute::class);

$routes->scope('/', function (RouteBuilder $builder) {   

    $builder->connect('login',['controller' => 'Auth','action' => 'login']);
    $builder->resources('Contato', ['path' => 'contato']);    
    
    $builder->fallbacks();
});

