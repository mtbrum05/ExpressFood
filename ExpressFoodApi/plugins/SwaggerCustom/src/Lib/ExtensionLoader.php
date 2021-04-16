<?php
declare(strict_types=1);

namespace SwaggerCustom\Lib;

use SwaggerCustom\Lib\Extension\ExtensionInterface;

class ExtensionLoader
{
    private const EXTENSIONS = [
        '\SwaggerCustom\Lib\Extension\CakeSearch\Extension',
    ];

    /**
     * Loads extensions from self::EXTENSIONS
     *
     * @return void
     */
    public static function load(): void
    {
        foreach (self::EXTENSIONS as $extension) {
            $instance = new $extension();

            if (!$instance instanceof ExtensionInterface) {
                triggerWarning("$extension must implement ExtensionInterface");
                continue;
            }

            if (!$instance->isSupported()) {
                continue;
            }

            $instance->loadAnnotations();
            $instance->registerListeners();
        }
    }
}
