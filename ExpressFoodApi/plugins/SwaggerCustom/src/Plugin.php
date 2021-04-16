<?php
declare(strict_types=1);

namespace SwaggerCustom;

use Cake\Console\CommandCollection;
use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\PluginApplicationInterface;
use SwaggerCustom\Command as Commands;
use SwaggerCustom\Lib\AnnotationLoader;
use SwaggerCustom\Lib\ExtensionLoader;

/**
 * Class Plugin
 *
 * @package SwaggerCustom
 */
class Plugin extends BasePlugin
{
    /**
     * @param \Cake\Core\PluginApplicationInterface $app PluginApplicationInterface
     * @return void
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        if (file_exists(CONFIG . 'swagger_bake.php')) {
            Configure::load('swagger_bake', 'default');
            AnnotationLoader::load();
            ExtensionLoader::load();

            return;
        }

        if (PHP_SAPI !== 'cli') {
            triggerWarning('SwaggerCustom configuration file `config/swagger_bake.php` is missing');
        }
    }

    /**
     * @param \Cake\Console\CommandCollection $commands CommandCollection
     * @return \Cake\Console\CommandCollection
     */
    public function console(CommandCollection $commands): CommandCollection
    {
        $commands->add('swagger routes', Commands\RouteCommand::class);
        $commands->add('swagger bake', Commands\BakeCommand::class);
        $commands->add('swagger models', Commands\ModelCommand::class);
        $commands->add('swagger install', Commands\InstallCommand::class);

        return $commands;
    }
}
