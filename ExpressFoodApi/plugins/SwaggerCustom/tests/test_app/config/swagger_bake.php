<?php
return [
    'SwaggerCustom' => [
        'prefix' => '/',
        'yml' => '/config/swagger.yml',
        'json' => '/webroot/swagger.json',
        'webPath' => '/swagger.json',
        'hotReload' => false,
        'namespaces' => [
            'controllers' => ['\SwaggerCustomTest\App\\'],
            'entities' => ['\SwaggerCustomTest\App\\'],
        ]
    ]
];
