# Supported Events

The `SwaggerCustom.Operation.created` is dispatched each time a new [Operation](https://github.com/cnizzardini/cakephp-swagger-bake/blob/master/src/Lib/OpenApi/Operation.php) is created. Simply listen for the event: 

```php
EventManager::instance()
    ->on('SwaggerCustom.Operation.created', function (Event $event) {
        /** @var \SwaggerCustom\Lib\OpenApi\Operation $operation */
        $operation = $event->getSubject();
    });
```

The `SwaggerCustom.Schema.created` is dispatched each time a new [Schema](https://github.com/cnizzardini/cakephp-swagger-bake/blob/master/src/Lib/OpenApi/Schema.php) instance is created. Simply listen for the event: 

```php
EventManager::instance()
    ->on('SwaggerCustom.Schema.created', function (Event $event) {
        /** @var \SwaggerCustom\Lib\OpenApi\Schema $schema */
        $schema = $event->getSubject();
    });
```

The `SwaggerCustom.initialize` is dispatched once, just before [Swagger](https://github.com/cnizzardini/cakephp-swagger-bake/blob/master/src/Lib/Swagger.php) begins building OpenAPI from your routes, models, and annotations.

```php
EventManager::instance()
    ->on('SwaggerCustom.initialize', function (Event $event) {
        /** @var \SwaggerCustom\Lib\Swagger $swagger */
        $swagger = $event->getSubject();
        $array = $swagger->getArray();
        $array['title'] = 'A new title';
        $swagger->setArray($array);
    });
```

The `SwaggerCustom.beforeRender` is dispatched once, just before [Swagger](https://github.com/cnizzardini/cakephp-swagger-bake/blob/master/src/Lib/Swagger.php) converts data to an OpenAPI array or json. 

```php
EventManager::instance()
    ->on('SwaggerCustom.beforeRender', function (Event $event) {
        /** @var \SwaggerCustom\Lib\Swagger $swagger */
        $swagger = $event->getSubject();
        $array = $swagger->getArray();
        $array['title'] = 'A new title';
        $swagger->setArray($array);
    });
```