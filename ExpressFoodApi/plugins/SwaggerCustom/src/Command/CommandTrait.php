<?php
declare(strict_types=1);

namespace SwaggerCustom\Command;

use Cake\Core\Configure;
use SwaggerCustom\Lib\AnnotationLoader;
use SwaggerCustom\Lib\Exception\SwaggerCustomRunTimeException;
use SwaggerCustom\Lib\ExtensionLoader;

trait CommandTrait
{
    /**
     * Loads configuration
     *
     * @param string $config your applications swagger_bake config
     * @return void
     * @throws \SwaggerCustom\Lib\Exception\SwaggerCustomRunTimeException
     */
    public function loadConfig(string $config = 'swagger_bake'): void
    {
        if (!Configure::load($config, 'default')) {
            throw new SwaggerCustomRunTimeException(
                "SwaggerCustom configuration file `$config` is missing"
            );
        }

        AnnotationLoader::load();
        ExtensionLoader::load();
    }
}
